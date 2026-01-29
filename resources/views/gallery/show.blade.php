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
            {{-- Grid Layout (Default) --}}
            @if($gallery->layout === 'grid')
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

            {{-- Masonry Layout --}}
            @elseif($gallery->layout === 'masonry')
                <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                    @foreach ($gallery->images as $image)
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition group break-inside-avoid">
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="{{ $image->alt_text }}" 
                                    class="w-full h-auto">
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

            {{-- List Layout --}}
            @elseif($gallery->layout === 'list')
                <div class="space-y-6">
                    @foreach ($gallery->images as $image)
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition group">
                            <div class="flex flex-col sm:flex-row">
                                <div class="relative sm:w-1/3">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        alt="{{ $image->alt_text }}" 
                                        class="w-full h-64 sm:h-full object-cover">
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
                                <div class="p-6 sm:w-2/3 flex items-center">
                                    <div>
                                        @if($image->alt_text)
                                            <p class="text-lg text-gray-800 font-medium mb-2">{{ $image->alt_text }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">Image #{{ $loop->iteration }} in gallery</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            {{-- Carousel Layout --}}
            @elseif($gallery->layout === 'carousel')
                <div id="carousel" class="relative bg-white rounded-xl overflow-hidden shadow-xl">
                    <div class="relative h-96 sm:h-[600px]">
                        @foreach ($gallery->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="{{ $image->alt_text }}" 
                                    class="w-full h-full object-contain bg-gray-900">
                                @if($image->alt_text)
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                                        <p class="text-white text-lg">{{ $image->alt_text }}</p>
                                    </div>
                                @endif
                                @can('update', $gallery)
                                    <form method="POST" action="{{ route('gallery.deleteImage', $image) }}" class="absolute top-4 right-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            onclick="return confirm('Delete this image?')"
                                            class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigation Buttons --}}
                    @if($gallery->images->count() > 1)
                        <button onclick="previousSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        {{-- Indicators --}}
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                            @foreach ($gallery->images as $index => $image)
                                <button onclick="goToSlide({{ $index }})" 
                                    class="indicator w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }} hover:bg-white transition">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <script>
                    let currentSlide = 0;
                    const slides = document.querySelectorAll('.carousel-item');
                    const indicators = document.querySelectorAll('.indicator');

                    function showSlide(n) {
                        slides.forEach((slide, index) => {
                            slide.classList.toggle('opacity-100', index === n);
                            slide.classList.toggle('opacity-0', index !== n);
                        });
                        indicators.forEach((indicator, index) => {
                            indicator.classList.toggle('bg-white', index === n);
                            indicator.classList.toggle('bg-white/50', index !== n);
                        });
                    }

                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % slides.length;
                        showSlide(currentSlide);
                    }

                    function previousSlide() {
                        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                        showSlide(currentSlide);
                    }

                    function goToSlide(n) {
                        currentSlide = n;
                        showSlide(currentSlide);
                    }

                    // Auto-advance carousel every 5 seconds
                    setInterval(nextSlide, 5000);

                    // Keyboard navigation
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowLeft') previousSlide();
                        if (e.key === 'ArrowRight') nextSlide();
                    });
                </script>
            @endif
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
