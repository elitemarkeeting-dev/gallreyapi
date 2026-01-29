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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Deployment</p>
                            <p class="text-sm font-semibold text-blue-600">Live ✓</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Quick Actions</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage your galleries and API access</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto mt-4 sm:mt-0">
                        <a href="{{ route('gallery.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition text-center text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Gallery
                        </a>
                        <a href="{{ route('gallery.export') }}" class="inline-flex items-center justify-center bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition text-center text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l-3 3m0 0l-3-3m3 3V10" />
                            </svg>
                            Export CSV
                        </a>
                        <a href="{{ route('tokens.index') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-center text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            API Tokens
                        </a>
                    </div>
                </div>

                @php
                    $tokenCount = auth()->user()->tokens()->count();
                @endphp
                
                @if($tokenCount > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-blue-800">You have <strong>{{ $tokenCount }}</strong> active API {{ Str::plural('token', $tokenCount) }}</span>
                            </div>
                            <a href="{{ route('tokens.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Manage →</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Deployment Status</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage and monitor your application deployments</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto mt-4 sm:mt-0">
                        <a href="https://github.com/ADC212006/gallreyapi/actions" target="_blank" class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-center text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c6.626 0 12 5.373 12 12 0 5.302-3.438 9.8-8.207 11.387-.599.111-.793-.261-.793-.577v-2.234c3.338.726 4.033-1.416 4.033-1.416.546-1.387 1.333-1.756 1.333-1.756 1.089-.745.083-.729-.083-.729-1.205.084-1.839 1.237-1.839 1.237-1.07 1.834-2.807 1.304-3.495.997-.107-.775-.418-1.305-.762-1.604 2.665-.305 5.467-1.334 5.467-5.931 0-1.311-.469-2.381-1.236-3.221.124-.303.535-1.524-.117-3.176 0 0-1.008-.322-3.301 1.23-.957-.266-1.983-.399-3.003-.404-1.02.005-2.047.138-3.006.404-2.291-1.552-3.297-1.23-3.297-1.23-.653 1.653-.242 2.874-.118 3.176-.77.84-1.235 1.911-1.235 3.221 0 4.609 2.807 5.624 5.479 5.921-.342.29-.646.873-.755 1.696-.68.306-2.401.826-3.461-1.011 0 0-.629-1.155-1.823-1.235 0 0-1.162-.015-.081.723 0 0 .778.389 1.318 1.79 0 0 .7 2.14 4.053 2.845v2.089c0 .322-.192.694-.801.576-4.765-1.588-8.199-6.086-8.199-11.386 0-6.627 5.373-12 12-12z"/>
                            </svg>
                            View on GitHub
                        </a>
                        <a href="https://github.com/ADC212006/gallreyapi/actions" target="_blank" class="inline-flex items-center justify-center bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition text-center text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Deploy Now
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Application Status</p>
                                <p class="text-sm text-green-700 mt-1">✓ Running & Live</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">Last Deployment</p>
                                <p class="text-sm text-blue-700 mt-1">Auto-deployed via GitHub Actions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>💡 Tip:</strong> Push changes to main branch on GitHub for automatic deployment. Monitor progress in the Actions tab.
                    </p>
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
