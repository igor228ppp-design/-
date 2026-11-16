<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// Публичные разделы: Главная и Категория
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/category/{categoryId?}', [NewsController::class, 'category'])->name('category');

// Для создания статьи требуется авторизованный автор (user_id обязателен в таблице texts)
Route::middleware('auth')->group(function () {
    Route::get('/journalist/{editId?}', [NewsController::class, 'journalist'])->name('journalist');
    Route::post('/journalist', [NewsController::class, 'store'])->name('texts.store');
    Route::put('/journalist/{text}', [NewsController::class, 'update'])->name('texts.update');
});

Route::get('/admin', [NewsController::class, 'admin'])->name('admin');

// Регистрация и вход по логину и паролю
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
