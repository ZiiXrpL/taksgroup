<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ------------------------------------------------------------------
// Hanya boleh diakses oleh guest (belum login).
// ------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');

    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

// ------------------------------------------------------------------
// Hanya boleh diakses oleh user yang sudah login (role apapun).
// ------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('profil', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::get('profil/foto', [ProfileController::class, 'photo'])
        ->name('profile.photo');

    Route::put('profil', [ProfileController::class, 'update'])
        ->name('profile.update');
});
