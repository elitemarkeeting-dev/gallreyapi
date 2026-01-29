<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tokens - Gallrey</title>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('dashboard') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-6 inline-block">← Back to Dashboard</a>
        
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">API Token Management</h2>
            <p class="text-gray-600">Generate and manage your API access tokens with specific permissions</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('token'))
            <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Save Your Token!</h3>
                        <p class="text-sm text-yellow-700 mb-3">Copy this token now - you won't be able to see it again.</p>
                        <div class="bg-white border border-yellow-300 rounded-lg p-4 flex items-center justify-between">
                            <code id="tokenValue" class="text-sm text-gray-800 break-all flex-1 mr-4">{{ session('token') }}</code>
                            <button onclick="copyToken()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition text-sm font-medium whitespace-nowrap">
                                Copy Token
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create New Token -->
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Create New API Token</h3>
            
            <form method="POST" action="{{ route('tokens.store') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Token Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                        placeholder="e.g., Mobile App, Third-party Integration"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Token Permissions</label>
                    <p class="text-sm text-gray-500 mb-4">Select the permissions this token should have. Multiple selections allowed.</p>
                    
                    <div class="space-y-3">
                        @foreach(\App\Http\Controllers\TokenController::TOKEN_ABILITIES as $key => $description)
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-purple-300 cursor-pointer transition
                                {{ $key === 'full-access' ? 'border-purple-300 bg-purple-50' : '' }}">
                                <input type="checkbox" name="abilities[]" value="{{ $key }}" 
                                    {{ old('abilities') && in_array($key, old('abilities')) ? 'checked' : '' }}
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

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                    Generate API Token
                </button>
            </form>
        </div>

        <!-- Existing Tokens -->
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Your API Tokens</h3>
            
            @if($tokens->count() > 0)
                <div class="space-y-4">
                    @foreach($tokens as $token)
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-purple-300 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1 mb-4 sm:mb-0">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $token->name }}</h4>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @if(in_array('*', $token->abilities))
                                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                                Full Access
                                            </span>
                                        @else
                                            @foreach($token->abilities as $ability)
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    {{ ucfirst(str_replace('-', ' ', $ability)) }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        Created: {{ $token->created_at->format('M d, Y - H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Last used: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-2 sm:ml-4">
                                    <a href="{{ route('tokens.edit', $token->id) }}" 
                                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-sm text-center\">
                                        Edit Permissions
                                    </a>
                                    
                                    <form method="POST" action="{{ route('tokens.regenerate', $token->id) }}" class="w-full sm:w-auto">
                                        @csrf
                                        <button type="submit" 
                                            onclick="return confirm('This will create a new token and delete the old one. Continue?')\"\n                                            class=\"w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm\">
                                            Regenerate
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('tokens.destroy', $token->id) }}" class="w-full sm:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            onclick="return confirm('Are you sure you want to delete this token? This action cannot be undone.')"
                                            class="w-full sm:w-auto bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium text-sm">
                                            Delete Token
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No API tokens yet</h3>
                    <p class="mt-2 text-gray-500">Create your first API token to start using the API.</p>
                </div>
            @endif
        </div>

        <!-- API Documentation -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">📖 How to Use Your API Token</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p><strong>1. Include the token in your requests:</strong></p>
                <pre class="bg-white border border-blue-300 rounded p-3 overflow-x-auto text-xs"><code>Authorization: Bearer YOUR_TOKEN_HERE</code></pre>
                
                <p class="mt-4"><strong>2. Available Endpoints:</strong></p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li><code>GET /api/galleries</code> - List all galleries (requires gallery-read or full-access)</li>
                    <li><code>GET /api/galleries/{id}</code> - Get gallery details (requires gallery-read or full-access)</li>
                    <li><code>GET /api/galleries/{id}/images</code> - Get gallery images (requires gallery-read or full-access)</li>
                    <li><code>GET /api/user</code> - Get user profile (requires user-read or full-access)</li>
                </ul>

                <p class="mt-4"><strong>3. Example cURL Request:</strong></p>
                <pre class="bg-white border border-blue-300 rounded p-3 overflow-x-auto text-xs"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
     {{ url('/api/galleries') }}</code></pre>
            </div>
        </div>

        <!-- Ready-Made Templates Section -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg p-6 mt-8">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <h3 class="text-lg font-semibold text-purple-900">🎨 Ready-Made Templates</h3>
            </div>
            <p class="text-sm text-purple-800 mb-6">Copy and paste these code snippets to display your galleries on any website. Choose your preferred CSS framework:</p>
            
            <!-- CSS Framework Selector -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="showTemplate('vanilla')" id="btn-vanilla" class="template-btn px-4 py-2 rounded-lg font-medium transition bg-purple-600 text-white">
                    Plain HTML/CSS/JS
                </button>
                <button onclick="showTemplate('tailwind')" id="btn-tailwind" class="template-btn px-4 py-2 rounded-lg font-medium transition bg-white text-purple-600 border-2 border-purple-200 hover:bg-purple-50">
                    Tailwind CSS
                </button>
                <button onclick="showTemplate('bootstrap')" id="btn-bootstrap" class="template-btn px-4 py-2 rounded-lg font-medium transition bg-white text-purple-600 border-2 border-purple-200 hover:bg-purple-50">
                    Bootstrap 5
                </button>
                <button onclick="showTemplate('simple')" id="btn-simple" class="template-btn px-4 py-2 rounded-lg font-medium transition bg-white text-purple-600 border-2 border-purple-200 hover:bg-purple-50">
                    Simple Grid
                </button>
            </div>

            @if(session('token'))
                <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-3 mb-4 text-sm text-yellow-800">
                    ✨ Your token is pre-filled in the templates below. Just copy and paste!
                </div>
            @endif

            <!-- Vanilla JS Template -->
            <div id="template-vanilla" class="template-content">
                <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-gray-800">Plain HTML/CSS/JS - Gallery Display</h4>
                        <button onclick="copyCode('vanilla')" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition">
                            Copy Code
                        </button>
                    </div>
                    <pre id="code-vanilla" class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto text-xs"><code>&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Gallery Display&lt;/title&gt;
    &lt;style&gt;
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .gallery-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .gallery-card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .gallery-image { width: 100%; height: 200px; object-fit: cover; }
        .gallery-info { padding: 15px; }
        .gallery-title { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 8px; }
        .gallery-description { color: #666; font-size: 14px; margin-bottom: 10px; }
        .gallery-meta { color: #999; font-size: 12px; }
        .loading { text-align: center; padding: 40px; color: #666; }
        .error { background: #fee; border: 1px solid #fcc; color: #c33; padding: 15px; border-radius: 8px; text-align: center; }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container"&gt;
        &lt;h1&gt;My Galleries&lt;/h1&gt;
        &lt;div id="gallery-container" class="loading"&gt;Loading galleries...&lt;/div&gt;
    &lt;/div&gt;

    &lt;script&gt;
        const API_URL = '{{ url('/api/galleries') }}';
        const API_TOKEN = '{{ session('token') ?? 'YOUR_TOKEN_HERE' }}';

        async function fetchGalleries() {
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch galleries');
                
                const data = await response.json();
                displayGalleries(data.data);
            } catch (error) {
                document.getElementById('gallery-container').innerHTML = 
                    `&lt;div class="error"&gt;Error loading galleries: ${error.message}&lt;/div&gt;`;
            }
        }

        function displayGalleries(galleries) {
            const container = document.getElementById('gallery-container');
            
            if (galleries.length === 0) {
                container.innerHTML = '&lt;p class="loading"&gt;No galleries found.&lt;/p&gt;';
                return;
            }

            container.className = 'gallery-grid';
            container.innerHTML = galleries.map(gallery =&gt; `
                &lt;div class="gallery-card"&gt;
                    ${gallery.images &amp;&amp; gallery.images.length &gt; 0 
                        ? `&lt;img src="${gallery.images[0].url}" alt="${gallery.name}" class="gallery-image"&gt;`
                        : '&lt;div style="height:200px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;"&gt;No Images&lt;/div&gt;'}
                    &lt;div class="gallery-info"&gt;
                        &lt;h3 class="gallery-title"&gt;${gallery.name}&lt;/h3&gt;
                        &lt;p class="gallery-description"&gt;${gallery.description || 'No description'}&lt;/p&gt;
                        &lt;p class="gallery-meta"&gt;${gallery.images ? gallery.images.length : 0} images&lt;/p&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            `).join('');
        }

        // Load galleries on page load
        fetchGalleries();
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                </div>
            </div>

            <!-- Tailwind CSS Template -->
            <div id="template-tailwind" class="template-content hidden">
                <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-gray-800">Tailwind CSS - Gallery Display</h4>
                        <button onclick="copyCode('tailwind')" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition">
                            Copy Code
                        </button>
                    </div>
                    <pre id="code-tailwind" class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto text-xs"><code>&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Gallery Display&lt;/title&gt;
    &lt;script src="https://cdn.tailwindcss.com"&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body class="bg-gray-50"&gt;
    &lt;div class="max-w-7xl mx-auto px-4 py-8"&gt;
        &lt;h1 class="text-4xl font-bold text-gray-800 mb-8 text-center"&gt;My Galleries&lt;/h1&gt;
        &lt;div id="gallery-container" class="text-center py-10 text-gray-600"&gt;Loading galleries...&lt;/div&gt;
    &lt;/div&gt;

    &lt;script&gt;
        const API_URL = '{{ url('/api/galleries') }}';
        const API_TOKEN = '{{ session('token') ?? 'YOUR_TOKEN_HERE' }}';

        async function fetchGalleries() {
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch galleries');
                
                const data = await response.json();
                displayGalleries(data.data);
            } catch (error) {
                document.getElementById('gallery-container').innerHTML = 
                    `&lt;div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"&gt;Error: ${error.message}&lt;/div&gt;`;
            }
        }

        function displayGalleries(galleries) {
            const container = document.getElementById('gallery-container');
            
            if (galleries.length === 0) {
                container.innerHTML = '&lt;p class="text-gray-500"&gt;No galleries found.&lt;/p&gt;';
                return;
            }

            container.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';
            container.innerHTML = galleries.map(gallery =&gt; `
                &lt;div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"&gt;
                    ${gallery.images &amp;&amp; gallery.images.length &gt; 0 
                        ? `&lt;img src="${gallery.images[0].url}" alt="${gallery.name}" class="w-full h-48 object-cover"&gt;`
                        : '&lt;div class="w-full h-48 bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold"&gt;No Images&lt;/div&gt;'}
                    &lt;div class="p-5"&gt;
                        &lt;h3 class="text-xl font-bold text-gray-800 mb-2"&gt;${gallery.name}&lt;/h3&gt;
                        &lt;p class="text-gray-600 text-sm mb-3"&gt;${gallery.description || 'No description'}&lt;/p&gt;
                        &lt;p class="text-gray-500 text-xs"&gt;${gallery.images ? gallery.images.length : 0} images&lt;/p&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            `).join('');
        }

        fetchGalleries();
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                </div>
            </div>

            <!-- Bootstrap Template -->
            <div id="template-bootstrap" class="template-content hidden">
                <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-gray-800">Bootstrap 5 - Gallery Display</h4>
                        <button onclick="copyCode('bootstrap')" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition">
                            Copy Code
                        </button>
                    </div>
                    <pre id="code-bootstrap" class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto text-xs"><code>&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Gallery Display&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;/head&gt;
&lt;body class="bg-light"&gt;
    &lt;div class="container py-5"&gt;
        &lt;h1 class="text-center mb-5"&gt;My Galleries&lt;/h1&gt;
        &lt;div id="gallery-container" class="text-center py-5"&gt;
            &lt;div class="spinner-border text-primary" role="status"&gt;
                &lt;span class="visually-hidden"&gt;Loading...&lt;/span&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script&gt;
        const API_URL = '{{ url('/api/galleries') }}';
        const API_TOKEN = '{{ session('token') ?? 'YOUR_TOKEN_HERE' }}';

        async function fetchGalleries() {
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch galleries');
                
                const data = await response.json();
                displayGalleries(data.data);
            } catch (error) {
                document.getElementById('gallery-container').innerHTML = 
                    `&lt;div class="alert alert-danger"&gt;Error loading galleries: ${error.message}&lt;/div&gt;`;
            }
        }

        function displayGalleries(galleries) {
            const container = document.getElementById('gallery-container');
            
            if (galleries.length === 0) {
                container.innerHTML = '&lt;p class="text-muted"&gt;No galleries found.&lt;/p&gt;';
                return;
            }

            container.className = 'row g-4';
            container.innerHTML = galleries.map(gallery =&gt; `
                &lt;div class="col-md-6 col-lg-4"&gt;
                    &lt;div class="card h-100 shadow-sm hover-shadow"&gt;
                        ${gallery.images &amp;&amp; gallery.images.length &gt; 0 
                            ? `&lt;img src="${gallery.images[0].url}" class="card-img-top" alt="${gallery.name}" style="height:200px;object-fit:cover;"&gt;`
                            : '&lt;div style="height:200px;background:linear-gradient(135deg,#667eea,#764ba2);" class="d-flex align-items-center justify-content-center text-white fw-bold"&gt;No Images&lt;/div&gt;'}
                        &lt;div class="card-body"&gt;
                            &lt;h5 class="card-title"&gt;${gallery.name}&lt;/h5&gt;
                            &lt;p class="card-text text-muted"&gt;${gallery.description || 'No description'}&lt;/p&gt;
                            &lt;p class="card-text"&gt;&lt;small class="text-muted"&gt;${gallery.images ? gallery.images.length : 0} images&lt;/small&gt;&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            `).join('');
        }

        fetchGalleries();
    &lt;/script&gt;
    &lt;style&gt;
        .hover-shadow { transition: all 0.3s; }
        .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    &lt;/style&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                </div>
            </div>

            <!-- Simple Grid Template -->
            <div id="template-simple" class="template-content hidden">
                <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-gray-800">Simple Grid - Minimal Code</h4>
                        <button onclick="copyCode('simple')" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition">
                            Copy Code
                        </button>
                    </div>
                    <pre id="code-simple" class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto text-xs"><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Galleries&lt;/title&gt;
    &lt;style&gt;
        body { font-family: sans-serif; padding: 20px; background: #fafafa; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1000px; margin: 0 auto; }
        .card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        @media(max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1 style="text-align:center;"&gt;Galleries&lt;/h1&gt;
    &lt;div id="galleries" class="grid"&gt;Loading...&lt;/div&gt;
    &lt;script&gt;
        fetch('{{ url('/api/galleries') }}', {
            headers: { 'Authorization': 'Bearer {{ session('token') ?? 'YOUR_TOKEN_HERE' }}' }
        })
        .then(r =&gt; r.json())
        .then(d =&gt; {
            document.getElementById('galleries').innerHTML = d.data.map(g =&gt; `
                &lt;div class="card"&gt;
                    ${g.images &amp;&amp; g.images.length &gt; 0 ? `&lt;img src="${g.images[0].url}" alt="${g.name}" style="width:100%;height:150px;object-fit:cover;border-radius:4px;"&gt;` : ''}
                    &lt;h3&gt;${g.name}&lt;/h3&gt;
                    &lt;p&gt;${g.description || ''}&lt;/p&gt;
                    &lt;p style="font-size:12px;color:#999;"&gt;${g.images ? g.images.length : 0} images&lt;/p&gt;
                &lt;/div&gt;
            `).join('');
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                </div>
            </div>

            <div class="bg-purple-100 border border-purple-300 rounded-lg p-4 mt-4">
                <p class="text-sm text-purple-900">
                    <strong>💡 Tips:</strong><br>
                    • Replace <code class="bg-purple-200 px-2 py-1 rounded text-xs">YOUR_TOKEN_HERE</code> with your actual API token<br>
                    • These templates work standalone - just save as .html and open in browser<br>
                    • Customize colors, spacing, and styles to match your website<br>
                    • For production, store tokens securely on your backend
                </p>
            </div>
        </div>
    </div>

    <script>
        function copyToken() {
            const tokenValue = document.getElementById('tokenValue').innerText;
            navigator.clipboard.writeText(tokenValue).then(() => {
                const button = event.target;
                const originalText = button.innerText;
                button.innerText = 'Copied!';
                button.classList.add('bg-green-600', 'hover:bg-green-700');
                button.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
                
                setTimeout(() => {
                    button.innerText = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
                }, 2000);
            });
        }

        function showTemplate(type) {
            // Hide all templates
            document.querySelectorAll('.template-content').forEach(el => el.classList.add('hidden'));
            
            // Remove active class from all buttons
            document.querySelectorAll('.template-btn').forEach(btn => {
                btn.classList.remove('bg-purple-600', 'text-white');
                btn.classList.add('bg-white', 'text-purple-600', 'border-2', 'border-purple-200');
            });
            
            // Show selected template
            document.getElementById(`template-${type}`).classList.remove('hidden');
            
            // Activate selected button
            const activeBtn = document.getElementById(`btn-${type}`);
            activeBtn.classList.add('bg-purple-600', 'text-white');
            activeBtn.classList.remove('bg-white', 'text-purple-600', 'border-2', 'border-purple-200');
        }

        function copyCode(type) {
            const code = document.getElementById(`code-${type}`).innerText;
            navigator.clipboard.writeText(code).then(() => {
                const button = event.target;
                const originalText = button.innerText;
                button.innerText = '✓ Copied!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-purple-600');
                
                setTimeout(() => {
                    button.innerText = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-purple-600');
                }, 2000);
            });
        }
    </script>
</body>
</html>
