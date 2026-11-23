<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Move;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Mostrar partidas y jugadores disponibles.
     */
    public function index()
    {
        $userId = auth()->id();

        $games = Game::with(['creator', 'playerX', 'playerO'])
            ->where('status', 'waiting')
            ->latest()
            ->get();

        $availablePlayers = User::query()
            ->where('role', 'player')
            ->where('id', '!=', $userId)
            ->whereDoesntHave('gamesAsX', function ($query) {
                $query->whereIn('status', ['waiting', 'in_progress']);
            })
            ->whereDoesntHave('gamesAsO', function ($query) {
                $query->whereIn('status', ['waiting', 'in_progress']);
            })
            ->orderBy('name')
            ->get();

        $myActiveGames = Game::with(['playerX', 'playerO'])
            ->whereIn('status', ['waiting', 'in_progress'])
            ->where(function ($query) use ($userId) {
                $query->where('player_x_id', $userId)
                    ->orWhere('player_o_id', $userId);
            })
            ->latest()
            ->get();

        return view('games.index', compact(
            'games',
            'availablePlayers',
            'myActiveGames'
        ));
    }

    /**
     * Crear una partida.
     */
    public function create()
    {
        $user = auth()->user();

        $game = DB::transaction(function () use ($user) {

            // Bloquear el usuario para coordinar sus solicitudes.
            User::whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $hasActiveGame = Game::whereIn('status', [
                'waiting',
                'in_progress'
            ])
                ->where(function ($query) use ($user) {
                    $query->where('player_x_id', $user->id)
                        ->orWhere('player_o_id', $user->id);
                })
                ->exists();

            if ($hasActiveGame) {
                return null;
            }

            return Game::create([
                'creator_id' => $user->id,
                'player_x_id' => $user->id,
                'player_o_id' => null,
                'status' => 'waiting',
                'board' => array_fill(0, 9, null),
                'current_turn' => 'X',
            ]);
        });

        if (!$game) {
            return redirect()
                ->route('games.index')
                ->with('error', 'Ya tienes una partida activa.');
        }

        return redirect()->route('games.show', $game);
    }

    /**
     * Unirse a una partida.
     */
    public function join(Game $game)
    {
        $user = auth()->user();

        $joined = DB::transaction(function () use ($game, $user) {

            // Bloquear al usuario.
            User::whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Bloquear la partida.
            $game = Game::whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $game->status !== 'waiting' ||
                (int) $game->creator_id === (int) $user->id
            ) {
                return false;
            }

            // Comprobar que el usuario no tenga otra partida activa.
            $hasActiveGame = Game::whereIn('status', [
                'waiting',
                'in_progress'
            ])
                ->where(function ($query) use ($user) {
                    $query->where('player_x_id', $user->id)
                        ->orWhere('player_o_id', $user->id);
                })
                ->exists();

            if ($hasActiveGame) {
                return false;
            }

            $game->player_o_id = $user->id;
            $game->status = 'in_progress';
            $game->current_turn = 'X';
            $game->save();

            return true;
        });

        if (!$joined) {
            return back()->with(
                'error',
                'No puedes unirte: la partida ya no está disponible o ya tienes una partida activa.'
            );
        }

        return redirect()->route('games.show', $game);
    }

    /**
     * Mostrar el tablero.
     */
    public function show(Game $game)
    {
        $this->authorizePlayer($game);

        $game->load([
            'creator',
            'playerX',
            'playerO',
            'moves.user'
        ]);

        return view('games.show', compact('game'));
    }

    /**
     * Registrar un movimiento.
     */
    public function move(Request $request, Game $game)
    {
        $request->validate([
            'position' => 'required|integer|between:0,8',
        ]);

        $result = DB::transaction(function () use ($request, $game) {

            $game = Game::whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            $user = auth()->user();

            if ($game->status !== 'in_progress') {
                return 'La partida no está activa.';
            }

            $symbol = (int) $game->player_x_id === (int) $user->id
                ? 'X'
                : ((int) $game->player_o_id === (int) $user->id ? 'O' : null);

            if (!$symbol || $symbol !== $game->current_turn) {
                return 'No es tu turno.';
            }

            $board = $game->board;

            if (!is_array($board) || count($board) !== 9) {
                return 'El tablero no tiene un formato válido.';
            }

            $position = (int) $request->position;

            if ($board[$position] !== null) {
                return 'Esa casilla ya está ocupada.';
            }

            $board[$position] = $symbol;

            $moveNumber = $game->moves()->count() + 1;

            Move::create([
                'game_id' => $game->id,
                'user_id' => $user->id,
                'symbol' => $symbol,
                'position' => $position,
                'move_number' => $moveNumber,
            ]);

            $game->board = $board;

            $winningCombinations = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6],
            ];

            $winner = false;

            foreach ($winningCombinations as $combination) {
                if (
                    $board[$combination[0]] === $symbol &&
                    $board[$combination[1]] === $symbol &&
                    $board[$combination[2]] === $symbol
                ) {
                    $winner = true;
                    break;
                }
            }

            if ($winner) {
                $game->status = 'finished';
                $game->winner_id = $user->id;
                $game->result = $symbol . ' ganó';
                $game->finished_at = now();
            } elseif (!in_array(null, $board, true)) {
                $game->status = 'finished';
                $game->result = 'draw';
                $game->finished_at = now();
            } else {
                $game->current_turn = $symbol === 'X' ? 'O' : 'X';
            }

            $game->save();

            return null;
        });

        if ($result !== null) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $result,
                ], 422);
            }

            return back()->with('error', $result);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Movimiento realizado.',
            ]);
        }

        return redirect()->route('games.show', $game);
    }

    /**
     * Historial del jugador.
     */
    public function history()
    {
        $userId = auth()->id();

        $games = Game::with(['playerX', 'playerO', 'winner'])
            ->where(function ($query) use ($userId) {
                $query->where('player_x_id', $userId)
                    ->orWhere('player_o_id', $userId);
            })
            ->whereIn('status', ['finished', 'cancelled'])
            ->latest('finished_at')
            ->get();

        return view('games.history', compact('games'));
    }

    /**
     * Abandonar una partida.
     */
    public function leave(Game $game)
    {
        $this->authorizePlayer($game);

        $cancelled = DB::transaction(function () use ($game) {

            $game = Game::whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($game->status, ['waiting', 'in_progress'], true)) {
                return false;
            }

            $game->status = 'cancelled';
            $game->result = 'abandoned';
            $game->finished_at = now();
            $game->save();

            return true;
        });

        if (!$cancelled) {
            return back()->with(
                'error',
                'La partida ya terminó o fue cancelada.'
            );
        }

        return redirect()
            ->route('games.index')
            ->with('success', 'Has abandonado la partida.');
    }

    /**
     * Consultar el estado actualizado.
     */
    public function status(Game $game)
    {
        $this->authorizePlayer($game);

        $game->load(['playerX', 'playerO']);

        return response()->json([
            'board' => $game->board,
            'status' => $game->status,
            'current_turn' => $game->current_turn,
            'player_x' => $game->playerX?->name,
            'player_o' => $game->playerO?->name,
            'result' => $game->result,
            'winner_id' => $game->winner_id,
        ]);
    }

    /**
     * Verificar que el usuario pertenezca a la partida.
     */
    private function authorizePlayer(Game $game): void
    {
        $userId = auth()->id();

        abort_unless(
            (int) $game->player_x_id === (int) $userId ||
            (int) $game->player_o_id === (int) $userId,
            403,
            'No perteneces a esta partida.'
        );
    }
}
