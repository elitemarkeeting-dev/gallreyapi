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
        $isAuthenticated = auth()->check();

        $galleries = Gallery::with(['user:id,name,email', 'images'])
            ->select('id', 'name', 'slug', 'description', 'user_id', 'created_at', 'updated_at')
            ->paginate(15);

        $data = array_map(function ($gallery) use ($isAuthenticated) {
            if (! $isAuthenticated) {
                unset($gallery->created_at, $gallery->updated_at, $gallery->user);
            }

            return $gallery;
        }, $galleries->items());

        return response()->json([
            'success' => true,
            'data' => $data,
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
        $isAuthenticated = auth()->check();
        $gallery->load(['user:id,name,email', 'images']);

        $data = [
            'id' => $gallery->id,
            'name' => $gallery->name,
            'slug' => $gallery->slug,
            'description' => $gallery->description,
        ];

        if ($isAuthenticated) {
            $data['owner'] = [
                'id' => $gallery->user->id,
                'name' => $gallery->user->name,
                'email' => $gallery->user->email,
            ];
        }

        $data['images'] = $gallery->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/'.$image->image_path),
                'alt_text' => $image->alt_text,
                'position' => $image->position,
            ];
        });

        $data['images_count'] = $gallery->images->count();

        if ($isAuthenticated) {
            $data['created_at'] = $gallery->created_at;
            $data['updated_at'] = $gallery->updated_at;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Display images for a specific gallery.
     */
    public function images(Gallery $gallery): JsonResponse
    {
        $isAuthenticated = auth()->check();

        $images = $gallery->images->map(function ($image) use ($isAuthenticated) {
            $imageData = [
                'id' => $image->id,
                'url' => asset('storage/'.$image->image_path),
                'full_path' => asset('storage/'.$image->image_path),
                'alt_text' => $image->alt_text,
                'position' => $image->position,
            ];

            if ($isAuthenticated) {
                $imageData['created_at'] = $image->created_at;
            }

            return $imageData;
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
