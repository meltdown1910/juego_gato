<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi historial de partidas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                Juego del gato
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Mi historial de partidas</h2>
                <p class="text-muted mb-0">
                    Consulta los resultados de tus partidas.
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Volver al panel
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                @if($games->isEmpty())

                    <div class="text-center py-5">
                        <h5>No tienes partidas en tu historial.</h5>
                        <p class="text-muted">
                            Cuando termines alguna partida, aparecerá aquí.
                        </p>

                        <a href="{{ route('games.index') }}" class="btn btn-primary">
                            Ver partidas disponibles
                        </a>
                    </div>

                @else

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tu símbolo</th>
                                    <th>Oponente</th>
                                    <th>Resultado</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($games as $game)

                                    @php
                                        $userId = Auth::id();

                                        if ((int) $game->player_x_id === (int) $userId) {
                                            $mySymbol = 'X';
                                            $opponent = $game->playerO;
                                        } else {
                                            $mySymbol = 'O';
                                            $opponent = $game->playerX;
                                        }

                                        if ($game->result === 'draw') {
                                            $result = 'Empate';
                                            $color = 'warning';
                                        } elseif ($game->result === 'abandoned') {
                                            $result = 'Partida abandonada';
                                            $color = 'secondary';
                                        } elseif ($game->result === 'cancelled') {
                                            $result = 'Partida cancelada';
                                            $color = 'secondary';
                                        } elseif (
                                            $game->winner_id !== null &&
                                            (int) $game->winner_id === (int) $userId
                                        ) {
                                            $result = 'Victoria';
                                            $color = 'success';
                                        } else {
                                            $result = 'Derrota';
                                            $color = 'danger';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $game->id }}</td>

                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $mySymbol }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $opponent?->name ?? 'Jugador no disponible' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $color }}">
                                                {{ $result }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $game->finished_at
                                                ? $game->finished_at->format('d/m/Y H:i')
                                                : 'Sin fecha' }}
                                        </td>

                                        <td>
                                            <a
                                                href="{{ route('games.show', $game) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Ver partida
                                            </a>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                @endif

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
