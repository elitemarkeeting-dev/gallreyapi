<?php

use App\Http\Controllers\Api\GalleryApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Read-only Gallery API endpoints - Requires authentication
Route::prefix('galleries')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [GalleryApiController::class, 'index']);
    Route::get('/{gallery}', [GalleryApiController::class, 'show']);
    Route::get('/{gallery}/images', [GalleryApiController::class, 'images']);
});
