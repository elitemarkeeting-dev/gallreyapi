<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $galleries = Gallery::with('images', 'user')->paginate(12);
        return view('gallery.index', compact('galleries'));
    }

    public function show(Gallery $gallery): \Illuminate\View\View
    {
        $gallery->load('images');
        return view('gallery.show', compact('gallery'));
    }

    public function create(): \Illuminate\View\View
    {
        $this->authorize('create', Gallery::class);
        return view('gallery.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', Gallery::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images.*' => ['required', 'image', 'max:5120'],
        ]);

        $gallery = Gallery::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('galleries/' . $gallery->id, 'public');
                $gallery->images()->create([
                    'image_path' => $path,
                    'alt_text' => $validated['name'],
                ]);
            }
        }

        return redirect()->route('gallery.show', $gallery)->with('success', 'Gallery created successfully!');
    }

    public function edit(Gallery $gallery): \Illuminate\View\View
    {
        $this->authorize('update', $gallery);
        return view('gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $gallery);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $gallery->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('galleries/' . $gallery->id, 'public');
                $gallery->images()->create([
                    'image_path' => $path,
                    'alt_text' => $validated['name'],
                ]);
            }
        }

        return redirect()->route('gallery.show', $gallery)->with('success', 'Gallery updated successfully!');
    }

    public function destroy(Gallery $gallery): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $gallery);

        foreach ($gallery->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        Storage::disk('public')->deleteDirectory('galleries/' . $gallery->id);
        $gallery->delete();

        return redirect()->route('gallery.index')->with('success', 'Gallery deleted successfully!');
    }

    public function deleteImage(GalleryImage $image): \Illuminate\Http\RedirectResponse
    {
        $gallery = $image->gallery;
        $this->authorize('update', $gallery);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->route('gallery.show', $gallery)->with('success', 'Image deleted successfully!');
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $galleries = Gallery::with('images', 'user')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="galleries-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($galleries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Gallery ID', 'Name', 'Slug', 'Description', 'Owner', 'Images Count', 'Created At']);

            foreach ($galleries as $gallery) {
                fputcsv($file, [
                    $gallery->id,
                    $gallery->name,
                    $gallery->slug,
                    $gallery->description,
                    $gallery->user->name,
                    $gallery->images->count(),
                    $gallery->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
