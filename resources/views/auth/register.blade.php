<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Juego del Gato</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow border-0 rounded-4 p-4" style="max-width: 450px; width: 100%;">

            <div class="text-center mb-4">
                <h2 class="fw-bold">Crear cuenta</h2>
                <p class="text-muted">Regístrate para jugar al gato</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Registrarme
                </button>
            </form>

            <div class="text-center mt-3">
                <span class="text-muted">¿Ya tienes una cuenta?</span>
                <a href="{{ route('login') }}">Inicia sesión</a>
            </div>

        </div>
    </div>

</body>
</html>
