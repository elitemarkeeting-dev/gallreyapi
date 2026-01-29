<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-white text-xl font-bold">Gallrey</h1>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Dashboard</a>
                    <a href="{{ route('gallery.index') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Browse</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('gallery.show', $gallery) }}" class="text-purple-600 hover:text-purple-800 font-medium mb-6 inline-block">← Back to Gallery</a>
        
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Edit Gallery</h2>
            
            <form method="POST" action="{{ route('gallery.update', $gallery) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Gallery Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $gallery->name) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">{{ old('description', $gallery->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="layout" class="block text-sm font-medium text-gray-700 mb-2">Gallery Layout</label>
                    <select id="layout" name="layout" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        @foreach(\App\Models\Gallery::LAYOUTS as $key => $label)
                            <option value="{{ $key }}" {{ old('layout', $gallery->layout ?? 'grid') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose how your images will be displayed</p>
                    @error('layout')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add More Images (Optional)</label>
                    
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-500 transition cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Drag & drop images here or click to browse</p>
                        <p class="mt-1 text-xs text-gray-500">Support: JPG, PNG, GIF (Max 5MB each)</p>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </div>
                    
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div id="previewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-6"></div>
                </div>

                @if ($gallery->images->count() > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach ($gallery->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        alt="{{ $image->alt_text }}" 
                                        class="w-full h-24 object-cover rounded-lg shadow-md">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition rounded-lg flex items-center justify-center">
                                        <form method="POST" action="{{ route('gallery.deleteImage', $image) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                onclick="return confirm('Delete this image?')"
                                                class="opacity-0 group-hover:opacity-100 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                        Update Gallery
                    </button>
                    <a href="{{ route('gallery.show', $gallery) }}" 
                        class="flex-1 text-center bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('previewContainer');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
            fileInput.files = e.dataTransfer.files;
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            previewContainer.innerHTML = '';
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const preview = document.createElement('div');
                        preview.className = 'relative group';
                        preview.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs opacity-0 group-hover:opacity-100 transition">${file.name}</span>
                            </div>
                        `;
                        previewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>
