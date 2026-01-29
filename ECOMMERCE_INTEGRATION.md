# Gallery API - eCommerce Integration Templates

Ready-to-use templates for integrating galleries into Laravel eCommerce systems like Farmart, eCommerce Genius, etc.

## Quick Integration Options

### Option 1: Blade Component (Recommended for Laravel)
### Option 2: Widget/Section for Home Page
### Option 3: Standalone Page
### Option 4: Product Gallery Integration

---

## 1. Gallery Grid Component (Products Style)

**File: `resources/views/components/gallery-grid.blade.php`**

Paste this component and use it anywhere: `<x-gallery-grid :limit="8" />`

```blade
@props(['limit' => 12, 'columns' => 4])

<div class="gallery-grid-section">
    <div class="container">
        <div class="section-header text-center mb-4">
            <h2 class="section-title">Our Galleries</h2>
            <p class="section-subtitle">Explore our curated collections</p>
        </div>

        <div id="gallery-grid-{{ uniqid() }}" class="row g-3">
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const API_URL = '{{ config("app.url") }}/api/galleries';
    const API_TOKEN = '{{ env("GALLERY_API_TOKEN", "YOUR_TOKEN_HERE") }}';
    const LIMIT = {{ $limit }};
    const COLUMNS = {{ $columns }};
    
    async function loadGalleries() {
        try {
            const response = await fetch(API_URL, {
                headers: {
                    'Authorization': `Bearer ${API_TOKEN}`,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load galleries');
            
            const data = await response.json();
            const galleries = data.data.slice(0, LIMIT);
            
            displayGalleries(galleries);
        } catch (error) {
            console.error('Gallery Error:', error);
            document.querySelector('#gallery-grid-{{ uniqid() }}').innerHTML = 
                '<div class="col-12"><div class="alert alert-warning">Unable to load galleries</div></div>';
        }
    }
    
    function displayGalleries(galleries) {
        const container = document.querySelector('#gallery-grid-{{ uniqid() }}');
        const colClass = `col-sm-6 col-md-${12/COLUMNS} col-lg-${12/COLUMNS}`;
        
        container.innerHTML = galleries.map(gallery => `
            <div class="${colClass}">
                <div class="product-card gallery-card h-100">
                    <div class="product-card-image">
                        <a href="/gallery/${gallery.id}">
                            <img src="${gallery.cover_image || '/placeholder.jpg'}" 
                                 alt="${gallery.name}" 
                                 class="img-fluid" 
                                 loading="lazy">
                        </a>
                        ${gallery.is_public ? '<span class="badge badge-success">Public</span>' : ''}
                    </div>
                    <div class="product-card-body">
                        <h3 class="product-title">
                            <a href="/gallery/${gallery.id}">${gallery.name}</a>
                        </h3>
                        <p class="product-description">${gallery.description || ''}</p>
                        <div class="product-meta">
                            <span class="text-muted">
                                <i class="icon-image"></i> ${gallery.images?.length || 0} Images
                            </span>
                        </div>
                        <a href="/gallery/${gallery.id}" class="btn btn-sm btn-primary mt-2">
                            View Gallery
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    loadGalleries();
})();
</script>

<style>
.gallery-card { 
    border: 1px solid #eee; 
    border-radius: 8px; 
    overflow: hidden; 
    transition: all 0.3s; 
    background: white;
}
.gallery-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 5px 20px rgba(0,0,0,0.1); 
}
.gallery-card .product-card-image { 
    position: relative; 
    overflow: hidden; 
}
.gallery-card .product-card-image img { 
    width: 100%; 
    height: 250px; 
    object-fit: cover; 
}
.gallery-card .badge { 
    position: absolute; 
    top: 10px; 
    right: 10px; 
}
.gallery-card .product-card-body { 
    padding: 15px; 
}
.gallery-card .product-title a { 
    color: #333; 
    font-size: 16px; 
    font-weight: 600; 
    text-decoration: none; 
}
.gallery-card .product-title a:hover { 
    color: #7c3aed; 
}
.gallery-card .product-description { 
    font-size: 14px; 
    color: #666; 
    margin: 8px 0; 
}
.gallery-card .product-meta { 
    font-size: 13px; 
}
</style>
```

---

## 2. Home Page Widget (Copy to Home Template)

**Paste into: `resources/views/themes/your-theme/home.blade.php`**

```blade
<!-- Gallery Section -->
<section class="gallery-showcase py-5">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="section-title">Featured Galleries</h2>
                <p class="section-subtitle">Discover our curated collections</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="/galleries" class="btn btn-outline-primary">View All</a>
            </div>
        </div>
        
        <div id="home-gallery-widget" class="row g-4">
            <div class="col-12 text-center py-5">
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const widget = document.getElementById('home-gallery-widget');
    
    fetch('{{ config("app.url") }}/api/galleries', {
        headers: {
            'Authorization': 'Bearer {{ env("GALLERY_API_TOKEN") }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const galleries = data.data.slice(0, 6);
        
        widget.innerHTML = galleries.map(gallery => `
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="gallery-widget-item">
                    <a href="/gallery/${gallery.id}" class="d-block">
                        <img src="${gallery.cover_image || '/placeholder.jpg'}" 
                             alt="${gallery.name}" 
                             class="img-fluid rounded shadow-sm">
                        <h6 class="mt-2 text-center">${gallery.name}</h6>
                        <p class="text-muted text-center small">${gallery.images?.length || 0} items</p>
                    </a>
                </div>
            </div>
        `).join('');
    })
    .catch(err => widget.innerHTML = '');
})();
</script>
```

---

## 3. Single Gallery Page

**File: `resources/views/gallery/show.blade.php` or add to your routes**

```blade
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div id="gallery-detail" class="text-center py-5">
        <div class="spinner-border"></div>
    </div>
</div>

<script>
const galleryId = {{ $id ?? 'window.location.pathname.split("/").pop()' }};
const API_URL = '{{ config("app.url") }}/api/galleries/' + galleryId;
const API_TOKEN = '{{ env("GALLERY_API_TOKEN") }}';

fetch(API_URL, {
    headers: {
        'Authorization': `Bearer ${API_TOKEN}`,
        'Accept': 'application/json'
    }
})
.then(res => res.json())
.then(data => {
    const gallery = data.data;
    const container = document.getElementById('gallery-detail');
    
    container.innerHTML = `
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/galleries">Galleries</a></li>
                        <li class="breadcrumb-item active">${gallery.name}</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-lg-12">
                <h1 class="display-4 mb-3">${gallery.name}</h1>
                <p class="lead text-muted">${gallery.description || ''}</p>
                <div class="d-flex gap-3 justify-content-center mb-4">
                    <span class="badge bg-primary">${gallery.images?.length || 0} Images</span>
                    <span class="badge bg-success">${gallery.is_public ? 'Public' : 'Private'}</span>
                </div>
            </div>
        </div>
        
        <div class="row g-3" id="gallery-images">
            ${gallery.images?.map(image => `
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gallery-image-item">
                        <img src="${image.full_path || image.url || image.image_path}" 
                             alt="${image.alt_text || gallery.name}" 
                             class="img-fluid rounded shadow-sm"
                             onclick="openLightbox('${image.full_path || image.url || image.image_path}')">
                    </div>
                </div>
            `).join('') || '<p class="text-muted">No images in this gallery</p>'}
        </div>
    `;
});

function openLightbox(imageUrl) {
    // Integrate with your existing lightbox/modal system
    console.log('Open image:', imageUrl);
}
</script>

<style>
.gallery-image-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s;
}
.gallery-image-item img:hover {
    transform: scale(1.05);
}
</style>
@endsection
```

---

## 4. Product Gallery Tab Integration

**Add to product detail page tabs**

```blade
<!-- Add this tab to your product detail tabs -->
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#product-gallery">Gallery</a>
</li>

<!-- Tab Content -->
<div class="tab-pane fade" id="product-gallery">
    <div id="product-gallery-content" class="row g-3">
        <div class="col-12 text-center py-4">
            <div class="spinner-border"></div>
        </div>
    </div>
</div>

<script>
document.querySelector('a[href="#product-gallery"]').addEventListener('click', function() {
    const productId = {{ $product->id }};
    
    // Replace with your gallery ID logic
    const galleryId = productId; // or get from product meta
    
    fetch(`{{ config("app.url") }}/api/galleries/${galleryId}`, {
        headers: {
            'Authorization': 'Bearer {{ env("GALLERY_API_TOKEN") }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('product-gallery-content');
        container.innerHTML = data.data.images?.map(img => `
            <div class="col-6 col-md-3">
                <img src="${img.full_path || img.url}" class="img-fluid rounded" alt="${img.alt_text}">
            </div>
        `).join('') || '<p>No gallery images</p>';
    });
}, { once: true });
</script>
```

---

## 5. Shortcode Style (Easy Integration)

**Add to any page content:**

```html
<div class="gallery-shortcode" data-gallery-id="1" data-layout="grid" data-columns="4"></div>

<script src="{{ asset('js/gallery-shortcode.js') }}"></script>
```

**File: `public/js/gallery-shortcode.js`**

```javascript
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-shortcode').forEach(container => {
        const galleryId = container.dataset.galleryId;
        const layout = container.dataset.layout || 'grid';
        const columns = container.dataset.columns || '4';
        
        const API_URL = `${window.location.origin}/api/galleries/${galleryId}`;
        const API_TOKEN = container.dataset.token || 'YOUR_TOKEN';
        
        fetch(API_URL, {
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const gallery = data.data;
            const colClass = `col-${12/columns}`;
            
            container.innerHTML = `
                <div class="row g-3">
                    ${gallery.images?.map(img => `
                        <div class="${colClass}">
                            <img src="${img.full_path || img.url}" 
                                 class="img-fluid rounded" 
                                 alt="${img.alt_text}">
                        </div>
                    `).join('')}
                </div>
            `;
        });
    });
});
```

---

## Configuration

### 1. Add to `.env` file:
```env
GALLERY_API_TOKEN=your_generated_token_here
```

### 2. Create route (if needed):
```php
// routes/web.php
Route::get('/galleries', function() {
    return view('gallery.index');
})->name('galleries.index');

Route::get('/gallery/{id}', function($id) {
    return view('gallery.show', ['id' => $id]);
})->name('gallery.show');
```

---

## Usage Examples

### In Home Page:
```blade
<x-gallery-grid :limit="8" :columns="4" />
```

### In Any Blade Template:
```blade
@include('components.gallery-grid', ['limit' => 12, 'columns' => 3])
```

### With Custom Styling:
```html
<div class="my-custom-wrapper">
    <x-gallery-grid :limit="6" :columns="3" />
</div>
```

---

## Notes

1. Replace `YOUR_TOKEN_HERE` with actual API token from tokens page
2. Adjust CSS classes to match your theme (Bootstrap 5, Tailwind, etc.)
3. Modify image sizes and styling as needed
4. Add your existing lightbox/modal integration
5. Update API URL if using different domain

---

## WordPress Integration Templates

### WordPress Option 1: Shortcode Plugin

**File: `wp-content/plugins/gallery-api/gallery-api.php`**

```php
<?php
/**
 * Plugin Name: Gallery API Integration
 * Description: Display galleries from external API using shortcode [gallery_api]
 * Version: 1.0
 * Author: Your Name
 */

// Add shortcode [gallery_api id="1" columns="4" limit="12"]
function gallery_api_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
        'columns' => '4',
        'limit' => '12',
        'layout' => 'grid'
    ), $atts);
    
    $api_url = get_option('gallery_api_url', 'https://antiquewhite-crane-835327.hostingersite.com');
    $api_token = get_option('gallery_api_token', '');
    
    $unique_id = uniqid('gallery_');
    
    ob_start();
    ?>
    <div id="<?php echo $unique_id; ?>" class="gallery-api-wrapper" 
         data-id="<?php echo esc_attr($atts['id']); ?>"
         data-columns="<?php echo esc_attr($atts['columns']); ?>"
         data-limit="<?php echo esc_attr($atts['limit']); ?>"
         data-layout="<?php echo esc_attr($atts['layout']); ?>">
        <div class="text-center py-5">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    
    <script>
    (function() {
        const container = document.getElementById('<?php echo $unique_id; ?>');
        const galleryId = container.dataset.id;
        const columns = parseInt(container.dataset.columns);
        const limit = parseInt(container.dataset.limit);
        const layout = container.dataset.layout;
        
        const API_URL = '<?php echo $api_url; ?>/api/galleries' + (galleryId ? '/' + galleryId : '');
        const API_TOKEN = '<?php echo $api_token; ?>';
        
        fetch(API_URL, {
            headers: {
                'Authorization': 'Bearer ' + API_TOKEN,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            let galleries = galleryId ? [data.data] : data.data.slice(0, limit);
            displayGalleries(galleries, container, columns);
        })
        .catch(err => {
            container.innerHTML = '<div class="alert alert-danger">Unable to load galleries</div>';
        });
        
        function displayGalleries(galleries, container, cols) {
            const colClass = 12 / cols;
            let html = '<div class="row">';
            
            galleries.forEach(gallery => {
                if (gallery.images && gallery.images.length > 0) {
                    gallery.images.forEach(image => {
                        html += `
                            <div class="col-md-${colClass} mb-4">
                                <div class="gallery-item card h-100">
                                    <img src="${image.full_path || image.url || image.image_path}" 
                                         class="card-img-top" 
                                         alt="${image.alt_text || gallery.name}"
                                         style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">${gallery.name}</h5>
                                        <p class="card-text">${image.alt_text || ''}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
            });
            
            html += '</div>';
            container.innerHTML = html;
        }
    })();
    </script>
    
    <style>
    .gallery-item { transition: transform 0.3s; }
    .gallery-item:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('gallery_api', 'gallery_api_shortcode');

// Settings page
add_action('admin_menu', 'gallery_api_menu');
function gallery_api_menu() {
    add_options_page('Gallery API Settings', 'Gallery API', 'manage_options', 'gallery-api-settings', 'gallery_api_settings_page');
}

function gallery_api_settings_page() {
    if (isset($_POST['gallery_api_url'])) {
        update_option('gallery_api_url', sanitize_text_field($_POST['gallery_api_url']));
        update_option('gallery_api_token', sanitize_text_field($_POST['gallery_api_token']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $api_url = get_option('gallery_api_url', '');
    $api_token = get_option('gallery_api_token', '');
    ?>
    <div class="wrap">
        <h1>Gallery API Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="gallery_api_url">API URL</label></th>
                    <td>
                        <input type="url" name="gallery_api_url" id="gallery_api_url" 
                               value="<?php echo esc_attr($api_url); ?>" 
                               class="regular-text" 
                               placeholder="https://your-api.com">
                        <p class="description">Your gallery API base URL (without /api)</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="gallery_api_token">API Token</label></th>
                    <td>
                        <input type="text" name="gallery_api_token" id="gallery_api_token" 
                               value="<?php echo esc_attr($api_token); ?>" 
                               class="regular-text">
                        <p class="description">Your API authentication token</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        
        <hr>
        <h2>Usage Examples</h2>
        <p><strong>Show all galleries (limited to 12):</strong></p>
        <code>[gallery_api limit="12" columns="4"]</code>
        
        <p><strong>Show specific gallery:</strong></p>
        <code>[gallery_api id="1" columns="3"]</code>
        
        <p><strong>Show as masonry layout:</strong></p>
        <code>[gallery_api layout="masonry" columns="3"]</code>
    </div>
    <?php
}
?>
```

**Installation:**
1. Create folder: `wp-content/plugins/gallery-api/`
2. Save above code as `gallery-api.php`
3. Activate plugin in WordPress admin
4. Go to Settings → Gallery API
5. Enter your API URL and Token
6. Use shortcode in any post/page: `[gallery_api limit="8" columns="4"]`

---

### WordPress Option 2: Elementor Widget

**File: `wp-content/plugins/gallery-api-elementor/gallery-api-elementor.php`**

```php
<?php
/**
 * Plugin Name: Gallery API - Elementor Widget
 * Description: Elementor widget for Gallery API
 * Version: 1.0
 */

// Register Elementor Widget
add_action('elementor/widgets/register', function($widgets_manager) {
    
    class Gallery_API_Elementor_Widget extends \Elementor\Widget_Base {
        
        public function get_name() { return 'gallery_api'; }
        public function get_title() { return 'Gallery API'; }
        public function get_icon() { return 'eicon-gallery-grid'; }
        public function get_categories() { return ['general']; }
        
        protected function register_controls() {
            $this->start_controls_section('content_section', [
                'label' => 'Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]);
            
            $this->add_control('api_url', [
                'label' => 'API URL',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'https://antiquewhite-crane-835327.hostingersite.com',
            ]);
            
            $this->add_control('api_token', [
                'label' => 'API Token',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]);
            
            $this->add_control('gallery_id', [
                'label' => 'Gallery ID (leave empty for all)',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]);
            
            $this->add_control('columns', [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '6' => '6 Columns',
                ],
            ]);
            
            $this->add_control('limit', [
                'label' => 'Limit',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
            ]);
            
            $this->end_controls_section();
        }
        
        protected function render() {
            $settings = $this->get_settings_for_display();
            $unique_id = 'gallery_' . $this->get_id();
            ?>
            <div id="<?php echo $unique_id; ?>" class="elementor-gallery-api">
                <div class="elementor-loading">Loading galleries...</div>
            </div>
            
            <script>
            (function() {
                const API_URL = '<?php echo esc_js($settings['api_url']); ?>/api/galleries<?php echo $settings['gallery_id'] ? '/' . esc_js($settings['gallery_id']) : ''; ?>';
                const API_TOKEN = '<?php echo esc_js($settings['api_token']); ?>';
                const COLUMNS = <?php echo (int)$settings['columns']; ?>;
                const LIMIT = <?php echo (int)$settings['limit']; ?>;
                
                fetch(API_URL, {
                    headers: {
                        'Authorization': 'Bearer ' + API_TOKEN,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('<?php echo $unique_id; ?>');
                    const galleries = data.data ? (Array.isArray(data.data) ? data.data : [data.data]) : [];
                    const colClass = 12 / COLUMNS;
                    
                    let html = '<div class="row g-4">';
                    galleries.slice(0, LIMIT).forEach(gallery => {
                        if (gallery.images) {
                            gallery.images.forEach(image => {
                                html += `
                                    <div class="col-md-${colClass}">
                                        <div class="gallery-card">
                                            <img src="${image.full_path || image.url}" alt="${image.alt_text}" class="img-fluid">
                                            <div class="gallery-caption">${gallery.name}</div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    });
                    html += '</div>';
                    container.innerHTML = html;
                });
            })();
            </script>
            
            <style>
            .gallery-card { position: relative; overflow: hidden; border-radius: 8px; }
            .gallery-card img { width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s; }
            .gallery-card:hover img { transform: scale(1.1); }
            .gallery-caption { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 10px; }
            </style>
            <?php
        }
    }
    
    $widgets_manager->register(new Gallery_API_Elementor_Widget());
});
?>
```

**Usage in Elementor:**
1. Install and activate plugin
2. Edit page with Elementor
3. Drag "Gallery API" widget from sidebar
4. Configure API URL, Token, and display settings
5. Publish page

---

## Farmart Theme Integration

### Farmart Option 1: Homepage Featured Section

**File: `platform/themes/farmart/views/index.blade.php`**

Add this section anywhere in your homepage:

```blade
<!-- Gallery Featured Section -->
<section class="section--featured-galleries pt-60 pb-60 bg-grey-9">
    <div class="container">
        <div class="section__header style-1 mb-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="section__title">Featured Galleries</h2>
                    <p class="section__subtitle">Explore our curated collections</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('public.galleries') }}" class="btn btn--primary">
                        View All Galleries <i class="fi-rs-arrow-small-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div id="farmart-galleries" class="row product-grid-4">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
(function() {
    const API_URL = '{{ env("GALLERY_API_URL", "https://antiquewhite-crane-835327.hostingersite.com") }}/api/galleries';
    const API_TOKEN = '{{ env("GALLERY_API_TOKEN") }}';
    
    fetch(API_URL, {
        headers: {
            'Authorization': 'Bearer ' + API_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('farmart-galleries');
        const galleries = data.data.slice(0, 8);
        
        container.innerHTML = galleries.map(gallery => `
            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-12">
                <div class="product-cart-wrap mb-30 gallery-item">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="/gallery/${gallery.id}">
                                <img class="default-img" 
                                     src="${gallery.cover_image || '/vendor/core/plugins/ecommerce/images/placeholder.png'}" 
                                     alt="${gallery.name}">
                            </a>
                        </div>
                        ${gallery.is_public ? '<div class="product-badges product-badges-position product-badges-mrg"><span class="new">Public</span></div>' : ''}
                    </div>
                    <div class="product-content-wrap">
                        <h2><a href="/gallery/${gallery.id}">${gallery.name}</a></h2>
                        <div class="product-rate-cover">
                            <span class="font-small text-muted">
                                <i class="fi-rs-camera mr-5"></i>
                                ${gallery.images?.length || 0} Images
                            </span>
                        </div>
                        <div class="product-card-bottom">
                            <div class="product-price">
                                <span>${gallery.description || 'View Collection'}</span>
                            </div>
                            <div class="add-cart">
                                <a class="add" href="/gallery/${gallery.id}">
                                    <i class="fi-rs-eye mr-5"></i>View Gallery
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    })
    .catch(err => {
        document.getElementById('farmart-galleries').innerHTML = 
            '<div class="col-12"><div class="alert alert-warning">Unable to load galleries</div></div>';
    });
})();
</script>

<style>
.gallery-item .product-img img {
    width: 100%;
    height: 280px;
    object-fit: cover;
}
.gallery-item:hover {
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.05);
    transform: translateY(-3px);
    transition: all 0.3s ease;
}
</style>
@endpush
```

---

### Farmart Option 2: Gallery Product Type

**File: `platform/plugins/ecommerce/src/Http/Controllers/Customers/GalleryController.php`**

```php
<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GalleryController extends BaseController
{
    public function index()
    {
        $apiUrl = env('GALLERY_API_URL') . '/api/galleries';
        $apiToken = env('GALLERY_API_TOKEN');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Accept' => 'application/json'
            ])->get($apiUrl);
            
            $galleries = $response->successful() ? $response->json()['data'] : [];
        } catch (\Exception $e) {
            $galleries = [];
        }
        
        return view('plugins/ecommerce::customers.galleries.index', compact('galleries'));
    }
    
    public function show($id)
    {
        $apiUrl = env('GALLERY_API_URL') . '/api/galleries/' . $id;
        $apiToken = env('GALLERY_API_TOKEN');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Accept' => 'application/json'
            ])->get($apiUrl);
            
            $gallery = $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            $gallery = null;
        }
        
        if (!$gallery) {
            abort(404);
        }
        
        return view('plugins/ecommerce::customers.galleries.show', compact('gallery'));
    }
}
```

**File: `platform/themes/farmart/views/ecommerce/galleries/index.blade.php`**

```blade
@extends('plugins/ecommerce::customers.master')

@section('content')
    <div class="page-content pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <div class="mb-50">
                        <h1 class="heading-2 mb-10">Our Galleries</h1>
                        <h6 class="text-body">Explore our curated image collections</h6>
                    </div>
                    
                    <div class="row product-grid">
                        @forelse($galleries as $gallery)
                            <div class="col-lg-3 col-md-4 col-6 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="{{ route('public.gallery.show', $gallery['id']) }}">
                                                <img class="default-img" 
                                                     src="{{ $gallery['cover_image'] ?? theme_option('default_gallery_image') }}" 
                                                     alt="{{ $gallery['name'] }}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <h2>
                                            <a href="{{ route('public.gallery.show', $gallery['id']) }}">
                                                {{ $gallery['name'] }}
                                            </a>
                                        </h2>
                                        <div class="product-rate-cover">
                                            <span class="font-small text-muted">
                                                {{ count($gallery['images'] ?? []) }} Images
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">No galleries found</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

**Add routes in `platform/themes/farmart/routes/web.php`:**

```php
Route::get('galleries', [GalleryController::class, 'index'])->name('public.galleries');
Route::get('gallery/{id}', [GalleryController::class, 'show'])->name('public.gallery.show');
```

---

## Configuration for All Templates

Add to `.env` file:

```env
GALLERY_API_URL=https://antiquewhite-crane-835327.hostingersite.com
GALLERY_API_TOKEN=your_token_here
```

---

## Support

For multi-vendor setup, you can filter galleries by vendor ID:
```javascript
const API_URL = '/api/galleries?vendor_id=' + vendorId;
```
