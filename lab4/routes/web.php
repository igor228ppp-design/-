<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// Главная и Категория — доступны всем (включая обычного пользователя)
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/category/{categoryId?}', [NewsController::class, 'category'])->name('category');

// Раздел «Журналист» — доступен ролям journalist и admin через RoleMiddleware
Route::middleware(['auth', 'role:journalist,admin'])->group(function () {
    Route::get('/journalist/{editId?}', [NewsController::class, 'journalist'])->name('journalist');
    Route::post('/journalist', [NewsController::class, 'store'])->name('texts.store');
    Route::put('/journalist/{text}', [NewsController::class, 'update'])->name('texts.update');
});

// Раздел «Админ» — доступен только роли admin через RoleMiddleware
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/{editId?}', [AdminController::class, 'index'])->name('admin');
    Route::put('/texts/{text}', [AdminController::class, 'updateText'])->name('admin.texts.update');
    Route::patch('/texts/{text}/toggle-block', [AdminController::class, 'toggleBlock'])->name('admin.texts.toggle-block');
    Route::delete('/texts/{text}', [AdminController::class, 'destroyText'])->name('admin.texts.destroy');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
});

// Авторизация и регистрация (по логину и паролю)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
