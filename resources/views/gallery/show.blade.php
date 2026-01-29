<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gallery->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-white text-xl font-bold">Gallrey</h1>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Dashboard</a>
                        <a href="{{ route('gallery.index') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Browse</a>
                    @else
                        <a href="{{ route('gallery.index') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Browse</a>
                        <a href="{{ route('login') }}" class="bg-white bg-opacity-20 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-opacity-30 transition text-sm sm:text-base">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('gallery.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-6 inline-block">← Back to Galleries</a>
        
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div class="mb-4 sm:mb-0">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $gallery->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $gallery->description }}</p>
                    <p class="text-sm text-gray-500 mt-3">Created by {{ $gallery->user->name }}</p>
                </div>
                @can('update', $gallery)
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <a href="{{ route('gallery.edit', $gallery) }}" 
                            class="text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('gallery.destroy', $gallery) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this gallery?')"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm sm:text-base">
                                Delete
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>

        @if ($gallery->images->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($gallery->images as $image)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                alt="{{ $image->alt_text }}" 
                                class="w-full h-64 object-cover">
                            @can('update', $gallery)
                                <form method="POST" action="{{ route('gallery.deleteImage', $image) }}" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        onclick="return confirm('Delete this image?')"
                                        class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition opacity-0 group-hover:opacity-100 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                        @if($image->alt_text)
                            <div class="p-4">
                                <p class="text-sm text-gray-600">{{ $image->alt_text }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No images in this gallery</h3>
                <p class="mt-2 text-gray-500">This gallery doesn't contain any images yet.</p>
                @can('update', $gallery)
                    <div class="mt-6">
                        <a href="{{ route('gallery.edit', $gallery) }}" 
                            class="inline-flex items-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Images
                        </a>
                    </div>
                @endcan
            </div>
        @endif
    </div>
</body>
</html>
