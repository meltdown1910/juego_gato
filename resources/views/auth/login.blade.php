<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Juego del Gato</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow border-0 rounded-4 p-4" style="max-width: 450px; width: 100%;">

            <div class="text-center mb-4">
                <h2 class="fw-bold">Juego del Gato</h2>
                <p class="text-muted">Inicia sesión para jugar</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Iniciar sesión
                </button>
            </form>

            <div class="text-center mt-3">
                <span class="text-muted">¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}">Regístrate</a>
            </div>

        </div>
    </div>

</body>
</html>
