<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPlayer(): bool
    {
        return $this->role === 'player';
    }

    public function createdGames(): HasMany
    {
        return $this->hasMany(Game::class, 'creator_id');
    }

    public function gamesAsX(): HasMany
    {
        return $this->hasMany(Game::class, 'player_x_id');
    }

    public function gamesAsO(): HasMany
    {
        return $this->hasMany(Game::class, 'player_o_id');
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
