<?php

use Illuminate\Support\Facades\Route;

// Главная — выводятся все новости на сайте
Route::view('/', 'index')->name('home');

// Категория — отображается одна категория из общей ленты («Информационные технологии»)
Route::view('/category', 'category')->name('category');

// Журналист — верстка формы для написания и редактирования статей
Route::view('/journalist', 'journalist')->name('journalist');

// Админ — верстка панели управления статьями и ролями пользователей
Route::view('/admin', 'admin')->name('admin');
