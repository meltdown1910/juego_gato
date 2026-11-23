<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidas disponibles - Juego del Gato</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="{{ route('dashboard') }}" class="navbar-brand fw-bold">
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

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold">Partidas y jugadores</h2>
                <p class="text-muted mb-0">
                    Encuentra un rival y comienza a jugar.
                </p>
            </div>

            <form action="{{ route('games.create') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    + Crear partida
                </button>
            </form>
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


        {{-- MIS PARTIDAS ACTIVAS --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Mis partidas activas</h4>

                    <small class="text-muted">
                        Puedes continuar las partidas que todavía no han terminado.
                    </small>
                </div>

                <div class="card-body px-4">

                    @forelse ($myActiveGames as $activeGame)

                        <div class="d-flex justify-content-between align-items-center border-bottom py-3 flex-wrap gap-3">

                            <div>
                                <h5 class="mb-1">
                                    Partida #{{ $activeGame->id }}
                                </h5>

                                <small class="text-muted">
                                    Jugador X:
                                    {{ $activeGame->playerX?->name ?? 'No disponible' }}
                                    |
                                    Jugador O:
                                    {{ $activeGame->playerO?->name ?? 'Esperando jugador' }}
                                </small>

                                <div class="mt-2">
                                    @if($activeGame->status === 'waiting')
                                        <span class="badge bg-warning text-dark">
                                            Esperando jugador
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            En curso
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('games.show', $activeGame) }}"
                            class="btn btn-primary">
                                Continuar partida
                            </a>

                        </div>

                    @empty

                        <div class="text-center py-4">
                            <p class="text-muted mb-0">
                                No tienes partidas activas.
                            </p>
                        </div>

                    @endforelse

                </div>
            </div>

        {{-- PARTIDAS DISPONIBLES --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 p-4">
                <h4 class="fw-bold mb-0">Partidas disponibles</h4>
                <small class="text-muted">
                    Partidas esperando un segundo jugador.
                </small>
            </div>

            <div class="card-body px-4">

                @forelse ($games as $game)

                    <div class="d-flex justify-content-between align-items-center border-bottom py-3 flex-wrap gap-3">

                        <div>
                            <h5 class="mb-1">
                                Partida #{{ $game->id }}
                            </h5>

                            <small class="text-muted">
                                Creada por:
                                {{ $game->creator?->name ?? 'Usuario no disponible' }}
                            </small>
                        </div>

                        @if ((int) $game->creator_id !== (int) Auth::id())

                            <form action="{{ route('games.join', $game) }}" method="POST">
                                @csrf

                                <button type="submit" class="btn btn-success">
                                    Unirme
                                </button>
                            </form>

                        @else

                            <a href="{{ route('games.show', $game) }}" class="btn btn-outline-primary">
                                Ver mi partida
                            </a>

                        @endif

                    </div>

                @empty

                    <div class="text-center py-4">
                        <h5>No hay partidas disponibles</h5>

                        <p class="text-muted mb-0">
                            Puedes crear una nueva partida para comenzar.
                        </p>
                    </div>

                @endforelse

            </div>
        </div>

        {{-- JUGADORES DISPONIBLES --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 p-4">
                <h4 class="fw-bold mb-0">Jugadores disponibles</h4>

                <small class="text-muted">
                    Usuarios que actualmente no participan en partidas activas.
                </small>
            </div>

            <div class="card-body px-4">

                @forelse ($availablePlayers as $player)

                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                        <div>
                            <h5 class="mb-1">
                                {{ $player->name }}
                            </h5>

                            <small class="text-muted">
                                {{ $player->email }}
                            </small>
                        </div>

                        <span class="badge bg-success">
                            Disponible
                        </span>

                    </div>

                @empty

                    <div class="text-center py-4">
                        <h5>No hay otros jugadores disponibles</h5>

                        <p class="text-muted mb-0">
                            Por el momento no hay usuarios libres para jugar.
                        </p>
                    </div>

                @endforelse

            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Volver al panel
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
