<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-4">Registrar usuario</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
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
                    <input type="password" name="password" class="form-control"
                           required minlength="8">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required minlength="8">
                </div>

                <div class="mb-4">
                    <label class="form-label">Rol</label>
                    <select name="role" class="form-select" required>
                        <option value="player" selected>Jugador</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Registrar usuario
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary w-100 mt-2">
                    Volver a usuarios
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
