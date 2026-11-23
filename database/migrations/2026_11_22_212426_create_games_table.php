<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            // Usuarios participantes
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('player_x_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('player_o_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('winner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estado de la partida
            $table->string('status')->default('waiting');

            // Tablero de 9 posiciones
            $table->json('board')->nullable();

            // Turno actual: X u O
            $table->string('current_turn', 1)->nullable();

            // Resultado de la partida
            $table->string('result')->nullable();

            // Fecha de finalización
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
