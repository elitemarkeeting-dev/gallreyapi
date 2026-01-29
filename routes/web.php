<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest')->name('login.post');

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/galleries/export', [\App\Http\Controllers\GalleryController::class, 'export'])->name('gallery.export');
    Route::delete('/gallery-image/{image}', [\App\Http\Controllers\GalleryController::class, 'deleteImage'])->name('gallery.deleteImage');
    Route::resource('gallery', \App\Http\Controllers\GalleryController::class);
});

// Public gallery routes
Route::get('/galleries', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery}', [\App\Http\Controllers\GalleryController::class, 'show'])->name('gallery.show');
