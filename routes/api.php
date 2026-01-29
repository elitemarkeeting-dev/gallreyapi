<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Read-only Gallery API endpoints
Route::prefix('galleries')->group(function () {
    Route::get('/', [GalleryApiController::class, 'index']);
    Route::get('/{gallery}', [GalleryApiController::class, 'show']);
    Route::get('/{gallery}/images', [GalleryApiController::class, 'images']);
});
