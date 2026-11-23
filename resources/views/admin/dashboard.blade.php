<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de administración</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
            Juego del Gato - Administrador
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

    <h2 class="fw-bold mb-2">Panel de administración</h2>

    <p class="text-muted mb-4">
        Bienvenido al sistema de administración del Juego del Gato.
    </p>

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

    {{-- Estadísticas --}}
    <div class="row g-4 mb-5">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-muted">Usuarios registrados</h6>
                    <h2 class="fw-bold text-primary">
                        {{ $totalUsers }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-muted">Partidas totales</h6>
                    <h2 class="fw-bold text-success">
                        {{ $totalGames }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-muted">Partidas activas</h6>
                    <h2 class="fw-bold text-warning">
                        {{ $activeGames }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- Opciones administrativas --}}
    <h4 class="fw-bold mb-3">Administración del sistema</h4>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Administrar usuarios</h5>

                    <p class="text-muted">
                        Consultar, registrar, editar y eliminar cuentas.
                        También puedes administrar los roles.
                    </p>

                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-primary">
                        Gestionar usuarios
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Administrar partidas</h5>

                    <p class="text-muted">
                        Consultar todas las partidas y cancelar las que
                        todavía estén activas.
                    </p>

                    <a href="{{ route('admin.games.index') }}"
                       class="btn btn-success">
                        Gestionar partidas
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Historial general</h5>

                    <p class="text-muted">
                        Consultar los resultados de todos los jugadores,
                        incluyendo victorias, empates y partidas canceladas.
                    </p>

                    <a href="{{ route('admin.history') }}"
                       class="btn btn-dark">
                        Consultar historial
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Inicio del sistema</h5>

                    <p class="text-muted">
                        Regresar a la pantalla principal.
                    </p>

                    <a href="{{ route('dashboard') }}"
                       class="btn btn-outline-secondary">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
