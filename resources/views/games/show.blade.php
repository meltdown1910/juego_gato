
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partida #{{ $game->id }} - Juego del Gato</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 8px;
            justify-content: center;
        }

        .cell {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            cursor: pointer;
            color: #0d6efd;
        }

        .cell:disabled {
            cursor: not-allowed;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="{{ route('games.index') }}" class="navbar-brand fw-bold">
                Juego del Gato
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light" type="submit">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <div class="container py-5">

        <div class="text-center mb-4">
            <h2 class="fw-bold">Partida #{{ $game->id }}</h2>

            <p class="text-muted" id="players-info">
                {{ $game->playerX->name ?? 'Esperando jugador X' }}
                (X) contra
                {{ $game->playerO->name ?? 'Esperando jugador O' }}
                (O)
            </p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 450px;">
            <div class="card-body text-center p-4">

                <h5 class="mb-3">Tablero</h5>

                <div class="board mx-auto" id="game-board">
                    @foreach ($game->board ?? array_fill(0, 9, null) as $position => $cell)
                        <form action="{{ route('games.move', $game) }}" method="POST">
                            @csrf

                            <input type="hidden" name="position" value="{{ $position }}">

                            <button
                                type="submit"
                                class="cell"
                                {{ $cell !== null || $game->status !== 'in_progress' || $game->current_turn !== ($game->player_x_id === Auth::id() ? 'X' : 'O') ? 'disabled' : '' }}
                            >
                                {{ $cell }}
                            </button>
                        </form>
                    @endforeach
                </div>

                <div class="mt-4">
                    <div
                        class="alert alert-primary mb-0"
                        id="game-status"
                    >
                        @if ($game->status === 'waiting')
                            Esperando a otro jugador.
                        @elseif ($game->status === 'in_progress')
                            Turno actual: {{ $game->current_turn }}
                        @else
                            Partida finalizada: {{ $game->result }}
                        @endif
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">

                    <a href="{{ route('games.index') }}" class="btn btn-outline-primary">
                        Volver a partidas
                    </a>

                    @if (in_array($game->status, ['waiting', 'in_progress']))
                        <form
                            action="{{ route('games.leave', $game) }}"
                            method="POST"
                            onsubmit="return confirm('¿Seguro que deseas abandonar esta partida?')">

                            @csrf

                            <button type="submit" class="btn btn-danger">
                                Abandonar partida
                            </button>

                        </form>
                    @endif

                </div>

            </div>
        </div>

    </div>

    <script>
        const statusUrl = @json(route('games.status', $game));

        const boardElement = document.getElementById('game-board');
        const statusElement = document.getElementById('game-status');

        // Enviar movimientos sin recargar la página.
        boardElement.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                const button = form.querySelector('button');
                button.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        alert(data.message || 'No se pudo realizar el movimiento.');
                        await updateGame();
                        return;
                    }

                    await updateGame();

                } catch (error) {
                    console.error('Error al enviar movimiento:', error);
                    alert('No se pudo conectar con el servidor.');
                    await updateGame();
                }
            });
        });

        const mySymbol = @json((int) $game->player_x_id === (int) Auth::id() ? 'X' : 'O');

        async function updateGame() {
            try {
                const response = await fetch(statusUrl, {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    console.error('Error:', response.status);
                    return;
                }

                const game = await response.json();

                const forms = boardElement.querySelectorAll('form');

                forms.forEach((form, index) => {
                    const button = form.querySelector('button');

                    button.textContent = game.board[index] ?? '';

                    button.disabled =
                        game.board[index] !== null ||
                        game.status !== 'in_progress' ||
                        game.current_turn !== mySymbol;
                });

                if (game.status === 'waiting') {
                    statusElement.textContent = 'Esperando a otro jugador.';
                } else if (game.status === 'in_progress') {
                    statusElement.textContent =
                        'Turno actual: ' + game.current_turn;
                } else {
                    statusElement.textContent =
                        game.result === 'draw'
                            ? '¡Empate!'
                            : 'Partida finalizada: ' + (game.result ?? '');
                }

                document.getElementById('players-info').textContent =
                    (game.player_x ?? 'Esperando jugador X') +
                    ' (X) contra ' +
                    (game.player_o ?? 'Esperando jugador O') +
                    ' (O)';

            } catch (error) {
                console.error('Error de actualización:', error);
            }
        }

        updateGame();
        setInterval(updateGame, 1000);
    </script>

</body>
</html>
