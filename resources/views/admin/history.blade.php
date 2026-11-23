<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial general</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a href="{{ route('admin.dashboard') }}"
           class="navbar-brand fw-bold">
            Administración
        </a>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-light btn-sm">
            Volver al panel
        </a>
    </div>
</nav>

<div class="container py-5">

    <h2 class="fw-bold mb-2">Historial general de partidas</h2>
    <p class="text-muted mb-4">
        Consulta las partidas finalizadas y canceladas de todos los jugadores.
    </p>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Partidas finalizadas</p>
                    <h3 class="fw-bold">{{ $totalFinished }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Victorias</p>
                    <h3 class="fw-bold text-success">{{ $totalVictories }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Empates</p>
                    <h3 class="fw-bold text-primary">{{ $totalDraws }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Canceladas</p>
                    <h3 class="fw-bold text-danger">{{ $totalCancelled }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th class="p-3">ID</th>
                            <th>Jugador X</th>
                            <th>Jugador O</th>
                            <th>Ganador</th>
                            <th>Resultado</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($games as $game)
                            <tr>
                                <td class="p-3">{{ $game->id }}</td>

                                <td>
                                    {{ $game->playerX?->name ?? 'No asignado' }}
                                </td>

                                <td>
                                    {{ $game->playerO?->name ?? 'No asignado' }}
                                </td>

                                <td>
                                    @if ($game->winner)
                                        <span class="fw-semibold text-success">
                                            {{ $game->winner->name }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if ($game->result === 'draw')
                                        Empate
                                    @elseif ($game->result === 'abandoned')
                                        Abandonada
                                    @elseif ($game->result === 'cancelled')
                                        Cancelada
                                    @elseif ($game->winner)
                                        Victoria de {{ $game->winner->name }}
                                    @else
                                        {{ $game->result ?? 'Sin resultado' }}
                                    @endif
                                </td>

                                <td>
                                    @if ($game->status === 'finished')
                                        <span class="badge bg-success">
                                            Finalizada
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Cancelada
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $game->finished_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    Todavía no hay partidas en el historial.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>
