<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'creator_id',
        'player_x_id',
        'player_o_id',
        'winner_id',
        'status',
        'board',
        'current_turn',
        'result',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'board' => 'array',
            'finished_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function playerX(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_x_id');
    }

    public function playerO(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_o_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
