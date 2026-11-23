<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal - Juego del Gato</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand fw-bold">Juego del Gato</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <main class="container py-5">

        <div class="text-center mb-5">
            <h1 class="fw-bold">
                ¡Bienvenido, {{ Auth::user()->name }}!
            </h1>

            <p class="text-muted">
                Tu espacio para jugar al gato.
            </p>
        </div>

        <div class="row justify-content-center g-4">

            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body text-center p-4">
                        <h4 class="fw-bold">Partidas</h4>

                        <p class="text-muted">
                            Crea una partida o únete a otro jugador.
                        </p>

                        <a href="{{ route('games.index') }}"
                           class="btn btn-primary w-100">
                            Ver partidas
                        </a>

                        <a href="{{ route('games.history') }}" class="btn btn-outline-primary">
                            Mi historial
                        </a>

                    </div>
                </div>
            </div>

            @if (Auth::user()->isAdmin())
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body text-center p-4">
                            <h4 class="fw-bold">Administración</h4>

                            <p class="text-muted">
                                Accede a las funciones administrativas.
                            </p>

                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-dark w-100">
                                Panel de administrador
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="text-center mt-4">
            <span class="badge bg-success">
                Rol: {{ Auth::user()->role }}
            </span>
        </div>

    </main>

</body>
</html>
