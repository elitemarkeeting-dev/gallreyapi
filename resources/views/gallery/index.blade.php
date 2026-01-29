<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galleries</title>
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
                        <a href="{{ route('gallery.create') }}" class="bg-white bg-opacity-20 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-opacity-30 transition text-sm sm:text-base">Create Gallery</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white bg-opacity-20 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-opacity-30 transition text-sm sm:text-base">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">All Galleries</h2>
            @auth
                <a href="{{ route('gallery.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                    Export CSV
                </a>
            @endauth
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($galleries as $gallery)
                <a href="{{ route('gallery.show', $gallery) }}" class="block group">
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">
                        @if ($gallery->images->first())
                            <img src="{{ asset('storage/' . $gallery->images->first()->image_path) }}" alt="{{ $gallery->name }}" class="w-full h-48 sm:h-56 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-48 sm:h-56 bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center">
                                <span class="text-white text-lg font-medium">No Images</span>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-purple-600 transition">{{ $gallery->name }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $gallery->layout === 'grid' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $gallery->layout === 'masonry' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $gallery->layout === 'list' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $gallery->layout === 'carousel' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ \App\Models\Gallery::LAYOUTS[$gallery->layout ?? 'grid'] }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($gallery->description, 100) }}</p>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $gallery->images->count() }} images
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No galleries found</h3>
                    <p class="mt-2 text-gray-500">Get started by creating your first gallery.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $galleries->links() }}
        </div>
    </div>
</body>
</html>
