<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Mostrar todos los usuarios.
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar formulario para registrar un usuario.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Registrar un usuario desde administración.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,player',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Mostrar formulario para editar un usuario.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar los datos de un usuario.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => 'required|in:admin,player',
        ]);

        // Evitar que el administrador quite su propio rol.
        if (
            (int) $user->id === (int) auth()->id() &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withInput()
                ->with('error', 'No puedes quitarte tu propio rol de administrador.');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user)
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with(
                'error',
                'No puedes eliminar tu propia cuenta.'
            );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
