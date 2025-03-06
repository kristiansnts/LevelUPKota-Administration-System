<?php

use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('filament.admin.auth.login'));
Route::get('/login', fn () => redirect()->route('filament.admin.auth.login'))->name('login');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
     ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');
