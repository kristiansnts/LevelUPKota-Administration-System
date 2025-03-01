<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('filament.admin.auth.login'));
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');
