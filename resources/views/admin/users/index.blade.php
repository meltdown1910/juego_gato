<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar usuarios</title>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Usuarios registrados</h2>
            <p class="text-muted mb-0">
                Administra las cuentas y sus permisos.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="btn btn-primary">
            + Registrar usuario
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

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="p-3">ID</th>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="p-3">{{ $user->id }}</td>

                                <td class="fw-semibold">
                                    {{ $user->name }}
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if ($user->role === 'admin')
                                        <span class="badge bg-dark">
                                            Administrador
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            Jugador
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-warning btn-sm">
                                            Editar
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">
                                                Tu cuenta
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-4">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-muted mt-3">
        Total de usuarios: {{ $users->count() }}
    </p>

</div>

</body>
</html>
