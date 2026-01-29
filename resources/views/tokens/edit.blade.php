<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit API Token - Gallrey</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-white text-xl font-bold">Gallrey</h1>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Dashboard</a>
                    <a href="{{ route('gallery.index') }}" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Galleries</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200 transition text-sm sm:text-base">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('tokens.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-6 inline-block">← Back to Tokens</a>
        
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Edit Token Permissions</h2>
            <p class="text-gray-600">Update the permissions for "{{ $token->name }}"</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">Token Information</h3>
                <p class="text-sm text-blue-800"><strong>Name:</strong> {{ $token->name }}</p>
                <p class="text-sm text-blue-800"><strong>Created:</strong> {{ $token->created_at->format('M d, Y - H:i') }}</p>
                <p class="text-sm text-blue-800"><strong>Last Used:</strong> {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}</p>
            </div>

            <form method="POST" action="{{ route('tokens.update', $token->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Update Token Permissions</label>
                    <p class="text-sm text-gray-500 mb-4">Select the permissions this token should have. Multiple selections allowed.</p>
                    
                    <div class="space-y-3">
                        @foreach(\App\Http\Controllers\TokenController::TOKEN_ABILITIES as $key => $description)
                            @php
                                $isSelected = in_array($key, $token->abilities) || in_array('*', $token->abilities);
                            @endphp
                            <label class="flex items-start p-4 border-2 rounded-lg hover:border-purple-300 cursor-pointer transition
                                {{ $isSelected ? 'border-purple-300 bg-purple-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="abilities[]" value="{{ $key }}" 
                                    {{ $isSelected ? 'checked' : '' }}
                                    class="mt-1 mr-3 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <div>
                                    <span class="font-medium text-gray-800 block">{{ ucfirst(str_replace('-', ' ', $key)) }}</span>
                                    <span class="text-sm text-gray-600">{{ $description }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    @error('abilities')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                        Update Permissions
                    </button>
                    <a href="{{ route('tokens.index') }}" 
                        class="flex-1 bg-gray-300 text-gray-800 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Permission Reference -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Permission Reference</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p><strong>Gallery - Read Only:</strong> View galleries and their images</p>
                <p><strong>Gallery - Full Access:</strong> Create, edit, and delete galleries</p>
                <p><strong>User - Read Profile:</strong> Read user profile information</p>
                <p><strong>Full API Access:</strong> All permissions (overrides individual selections)</p>
            </div>
        </div>
    </div>
</body>
</html>
