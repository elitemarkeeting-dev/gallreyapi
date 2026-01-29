<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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

    // API Token Management
    Route::get('/tokens', [\App\Http\Controllers\TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [\App\Http\Controllers\TokenController::class, 'store'])->name('tokens.store');
    Route::get('/tokens/{token}/edit', [\App\Http\Controllers\TokenController::class, 'edit'])->name('tokens.edit');
    Route::put('/tokens/{token}', [\App\Http\Controllers\TokenController::class, 'update'])->name('tokens.update');
    Route::post('/tokens/{token}/regenerate', [\App\Http\Controllers\TokenController::class, 'regenerate'])->name('tokens.regenerate');
    Route::delete('/tokens/{token}', [\App\Http\Controllers\TokenController::class, 'destroy'])->name('tokens.destroy');
});
