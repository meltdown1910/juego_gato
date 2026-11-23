<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')
                ->constrained('games')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('symbol', 1);
            $table->unsignedTinyInteger('position');
            $table->unsignedTinyInteger('move_number');

            $table->timestamps();

            $table->unique(['game_id', 'position']);
            $table->unique(['game_id', 'move_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
