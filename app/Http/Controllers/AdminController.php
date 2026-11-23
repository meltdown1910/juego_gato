<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Panel principal de administración.
     */
    public function dashboard()
    {
        $totalUsers = User::count();

        $totalGames = Game::count();

        $activeGames = Game::whereIn('status', [
            'waiting',
            'in_progress'
        ])->count();

        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGames',
            'activeGames',
            'users'
        ));
    }

    /**
     * Mostrar todas las partidas.
     */
    public function games()
    {
        $games = Game::with([
            'creator',
            'playerX',
            'playerO',
            'winner'
        ])->latest()->get();

        return view('admin.games', compact('games'));
    }

    /**
     * Cancelar una partida activa.
     */
    public function cancelGame(Game $game)
    {
        $cancelled = DB::transaction(function () use ($game) {

            $game = Game::whereKey($game->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($game->status, [
                'waiting',
                'in_progress'
            ], true)) {
                return false;
            }

            $game->status = 'cancelled';
            $game->result = 'cancelled';
            $game->finished_at = now();

            $game->save();

            return true;
        });

        if (!$cancelled) {
            return back()->with(
                'error',
                'Esta partida ya terminó o fue cancelada.'
            );
        }

        return back()->with(
            'success',
            'La partida fue cancelada correctamente.'
        );
    }

    /**
     * Historial general de todas las partidas.
     */
    public function history()
    {
        $games = Game::with([
            'creator',
            'playerX',
            'playerO',
            'winner'
        ])
            ->whereIn('status', [
                'finished',
                'cancelled'
            ])
            ->latest('finished_at')
            ->get();

        $totalFinished = $games->count();

        $totalVictories = $games->whereNotNull('winner_id')->count();

        $totalDraws = $games->where('result', 'draw')->count();

        $totalCancelled = $games->where('status', 'cancelled')->count();

        return view('admin.history', compact(
            'games',
            'totalFinished',
            'totalVictories',
            'totalDraws',
            'totalCancelled'
        ));
    }
}
