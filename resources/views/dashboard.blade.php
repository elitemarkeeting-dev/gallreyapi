<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-white text-xl font-bold">Gallrey</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('gallery.index') }}" class="text-white hover:text-gray-200 transition">Browse Galleries</a>
                    <span class="text-white hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200 transition font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h2>
                    <p class="text-purple-100 mb-1"><strong>Username:</strong> {{ auth()->user()->username }}</p>
                    <p class="text-purple-100 mb-1"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    @if (auth()->user()->role)
                        <span class="inline-block bg-white bg-opacity-30 text-white px-4 py-1 rounded-full text-sm mt-3">
                            Role: {{ auth()->user()->role->name }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">My Galleries</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Gallery::where('user_id', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Images</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\GalleryImage::whereIn('gallery_id', \App\Models\Gallery::where('user_id', auth()->id())->pluck('id'))->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Role</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->role->name ?? 'User' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 sm:mb-0">Quick Actions</h3>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                        <a href="{{ route('gallery.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition text-center">
                            Create Gallery
                        </a>
                        <a href="{{ route('gallery.export') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition text-center">
                            Export CSV
                        </a>
                    </div>
                </div>

                @php
                    $myGalleries = \App\Models\Gallery::where('user_id', auth()->id())->with('images')->latest()->take(6)->get();
                @endphp

                @if($myGalleries->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($myGalleries as $gallery)
                            <a href="{{ route('gallery.show', $gallery) }}" class="block group">
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                                    @if ($gallery->images->first())
                                        <img src="{{ asset('storage/' . $gallery->images->first()->image_path) }}" alt="{{ $gallery->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center">
                                            <span class="text-white text-lg">No Images</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-800 group-hover:text-purple-600 transition">{{ $gallery->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $gallery->images->count() }} images</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('gallery.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">View All Galleries →</a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No galleries</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new gallery.</p>
                        <div class="mt-6">
                            <a href="{{ route('gallery.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Gallery
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
