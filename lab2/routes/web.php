<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// Основные разделы сайта
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/category/{categoryName?}', [NewsController::class, 'category'])->name('category');

Route::get('/journalist/{editId?}', [NewsController::class, 'journalist'])->name('journalist');
Route::post('/journalist', [NewsController::class, 'store'])->name('texts.store');
Route::put('/journalist/{text}', [NewsController::class, 'update'])->name('texts.update');

Route::get('/admin', [NewsController::class, 'admin'])->name('admin');

// Авторизация и регистрация (только по логину и паролю)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
