<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/registro', [AuthController::class, 'register'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Partidas de jugadores
    Route::get('/games', [GameController::class, 'index'])
        ->name('games.index');

    Route::post('/games', [GameController::class, 'create'])
        ->name('games.create');

    Route::post('/games/{game}/join', [GameController::class, 'join'])
        ->name('games.join');

    Route::get('/games/{game}', [GameController::class, 'show'])
        ->name('games.show');

    Route::post('/games/{game}/move', [GameController::class, 'move'])
        ->name('games.move');

    Route::get('/games/{game}/status', [GameController::class, 'status'])
        ->name('games.status');

    Route::post('/games/{game}/leave', [GameController::class, 'leave'])
        ->name('games.leave');

    Route::get('/historial', [GameController::class, 'history'])
        ->name('games.history');

    // Administración
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // CRUD de usuarios
        Route::get('/usuarios', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/usuarios/crear', [AdminUserController::class, 'create'])
            ->name('users.create');

        Route::post('/usuarios', [AdminUserController::class, 'store'])
            ->name('users.store');

        Route::get('/usuarios/{user}/editar', [AdminUserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/usuarios/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        Route::delete('/usuarios/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        // Administración de partidas
        Route::get('/partidas', [AdminController::class, 'games'])
            ->name('games.index');

        Route::delete('/partidas/{game}', [AdminController::class, 'cancelGame'])
            ->name('games.cancel');

        Route::get('/historial', [AdminController::class, 'history'])
            ->name('history');
    });
});
