<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                Administración
            </a>
        </div>
    </nav>

    <div class="container py-5">

        <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 600px;">

            <div class="card-body p-4">

                <h3 class="fw-bold mb-4">Editar usuario</h3>

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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rol</label>

                        <select name="role" class="form-select" required>
                            <option value="player"
                                {{ old('role', $user->role) === 'player' ? 'selected' : '' }}>
                                Jugador
                            </option>

                            <option value="admin"
                                {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                Administrador
                            </option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Guardar cambios
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>
</html>