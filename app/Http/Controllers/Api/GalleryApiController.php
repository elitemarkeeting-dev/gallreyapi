<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;

class GalleryApiController extends Controller
{
    /**
     * Display a listing of all galleries.
     */
    public function index(): JsonResponse
    {
        $galleries = Gallery::with(['user:id,name,email', 'images'])
            ->select('id', 'name', 'slug', 'description', 'user_id', 'created_at', 'updated_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $galleries->items(),
            'pagination' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
                'per_page' => $galleries->perPage(),
                'total' => $galleries->total(),
            ],
        ]);
    }

    /**
     * Display a specific gallery.
     */
    public function show(Gallery $gallery): JsonResponse
    {
        $gallery->load(['user:id,name,email', 'images']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'slug' => $gallery->slug,
                'description' => $gallery->description,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at,
                'owner' => [
                    'id' => $gallery->user->id,
                    'name' => $gallery->user->name,
                    'email' => $gallery->user->email,
                ],
                'images' => $gallery->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image_path),
                        'alt_text' => $image->alt_text,
                        'position' => $image->position,
                    ];
                }),
                'images_count' => $gallery->images->count(),
            ],
        ]);
    }

    /**
     * Display images for a specific gallery.
     */
    public function images(Gallery $gallery): JsonResponse
    {
        $images = $gallery->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'full_path' => asset('storage/' . $image->image_path),
                'alt_text' => $image->alt_text,
                'position' => $image->position,
                'created_at' => $image->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'gallery' => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'slug' => $gallery->slug,
            ],
            'data' => $images,
            'total' => $images->count(),
        ]);
    }
}
