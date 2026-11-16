<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Основная рабочая таблица статей (texts).
     * Обязательные проиндексированные внешние ключи на categories (restrictOnDelete)
     * и users (cascadeOnDelete).
     */
    public function up(): void
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('text');
            $table->foreignId('categorie_id')
                ->index()
                ->constrained('categories')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('texts');
    }
};
