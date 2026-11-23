<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar partidas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                Juego del Gato - Administración
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Administrar partidas</h2>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                Volver al panel
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Creador</th>
                                <th>Jugador X</th>
                                <th>Jugador O</th>
                                <th>Estado</th>
                                <th>Resultado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($games as $game)

                                <tr>
                                    <td>#{{ $game->id }}</td>

                                    <td>
                                        {{ $game->creator->name ?? 'Usuario eliminado' }}
                                    </td>

                                    <td>
                                        {{ $game->playerX->name ?? 'Sin jugador' }}
                                    </td>

                                    <td>
                                        {{ $game->playerO->name ?? 'Sin jugador' }}
                                    </td>

                                    <td>
                                        @if ($game->status === 'waiting')
                                            <span class="badge bg-warning text-dark">
                                                Esperando
                                            </span>
                                        @elseif ($game->status === 'in_progress')
                                            <span class="badge bg-primary">
                                                En curso
                                            </span>
                                        @elseif ($game->status === 'finished')
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
                                        {{ $game->result ?? 'Sin resultado' }}
                                    </td>

                                    <td>
                                        {{ $game->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        @if (in_array($game->status, ['waiting', 'in_progress']))
                                            <form
                                                action="{{ route('admin.games.cancel', $game) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Seguro que deseas cancelar esta partida?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Cancelar
                                                </button>

                                            </form>
                                        @else
                                            <span class="text-muted">Sin acciones</span>
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No hay partidas registradas.
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