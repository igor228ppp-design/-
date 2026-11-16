<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Основная рабочая таблица статей (texts).
     * Добавлено поле is_blocked для возможности блокировки/разблокировки статей администратором.
     */
    public function up(): void
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('text');
            $table->boolean('is_blocked')->default(false);
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
