<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Gallery Images - Professional Gallery Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section bg-gradient text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Professional Gallery Collection</h1>
                    <p class="lead mb-0">Explore our curated image galleries from all collections</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div id="gallery-stats" class="stats-box p-3 bg-white bg-opacity-10 rounded">
                        <div class="spinner-border spinner-border-sm text-white" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Container -->
    <div class="container py-5">
        <!-- Gallery by Gallery Section -->
        <div id="galleries-by-collection" class="mb-5">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading galleries...</span>
                </div>
                <p class="text-muted mt-3">Loading amazing galleries...</p>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="lightboxTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="lightboxImage" src="" alt="" class="img-fluid rounded">
                    <p class="text-white mt-3" id="lightboxCaption"></p>
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <button class="btn btn-outline-light" onclick="previousImage()">
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <span class="text-white" id="imageCounter"></span>
                    <button class="btn btn-outline-light" onclick="nextImage()">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = '{{ config("app.url") }}/api/galleries';
        const API_TOKEN = '{{ env("GALLERY_API_TOKEN", "2|T2YxHe4BvRWuplzgJ6SUWJYRTWLUmvKAMJHg9jZ77ce6c71f") }}';
        
        let allImages = [];
        let currentImageIndex = 0;
        const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));

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
                document.getElementById('galleries-by-collection').innerHTML = 
                    `<div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> Error loading galleries: ${error.message}
                    </div>`;
            }
        }

        function displayGalleries(galleries) {
            const container = document.getElementById('galleries-by-collection');
            
            // Calculate stats
            let totalImages = 0;
            galleries.forEach(gallery => {
                totalImages += gallery.images?.length || 0;
            });
            
            // Update stats
            document.getElementById('gallery-stats').innerHTML = `
                <div class="d-flex justify-content-around">
                    <div>
                        <h3 class="mb-0">${galleries.length}</h3>
                        <small>Galleries</small>
                    </div>
                    <div>
                        <h3 class="mb-0">${totalImages}</h3>
                        <small>Total Images</small>
                    </div>
                </div>
            `;

            if (galleries.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No galleries found.</div>';
                return;
            }

            // Build galleries HTML
            let html = '';
            let imageGlobalIndex = 0;

            galleries.forEach((gallery, galleryIndex) => {
                if (!gallery.images || gallery.images.length === 0) return;

                html += `
                    <div class="gallery-section mb-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h2 class="h3 mb-1">
                                    <i class="bi bi-images text-primary"></i> ${gallery.name}
                                </h2>
                                <p class="text-muted mb-0">
                                    ${gallery.description || 'Beautiful collection of images'}
                                    <span class="badge bg-primary ms-2">${gallery.images.length} Images</span>
                                    ${gallery.is_public ? '<span class="badge bg-success ms-1">Public</span>' : '<span class="badge bg-secondary ms-1">Private</span>'}
                                </p>
                            </div>
                        </div>
                        
                        <div class="row g-3 gallery-grid">
                `;

                gallery.images.forEach((image, imageIndex) => {
                    const imageUrl = image.full_path || image.url || image.image_path;
                    allImages.push({
                        url: imageUrl,
                        alt: image.alt_text || gallery.name,
                        gallery: gallery.name,
                        index: imageGlobalIndex++
                    });

                    html += `
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="gallery-item" onclick="openLightbox(${allImages.length - 1})">
                                <img src="${imageUrl}" 
                                     alt="${image.alt_text || gallery.name}" 
                                     class="img-fluid rounded shadow-sm"
                                     loading="lazy">
                                <div class="gallery-overlay">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                    <hr class="my-5">
                `;
            });

            container.innerHTML = html;
        }

        function openLightbox(index) {
            currentImageIndex = index;
            showImage(index);
            lightboxModal.show();
        }

        function showImage(index) {
            const image = allImages[index];
            document.getElementById('lightboxImage').src = image.url;
            document.getElementById('lightboxTitle').textContent = image.gallery;
            document.getElementById('lightboxCaption').textContent = image.alt;
            document.getElementById('imageCounter').textContent = `${index + 1} / ${allImages.length}`;
        }

        function previousImage() {
            currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
            showImage(currentImageIndex);
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % allImages.length;
            showImage(currentImageIndex);
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (lightboxModal._isShown) {
                if (e.key === 'ArrowLeft') previousImage();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'Escape') lightboxModal.hide();
            }
        });

        fetchGalleries();
    </script>

    <style>
        :root {
            --primary-color: #7c3aed;
            --secondary-color: #2563eb;
        }

        body {
            background-color: #f8f9fa;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stats-box h3 {
            font-size: 2rem;
            font-weight: bold;
        }

        .stats-box small {
            opacity: 0.9;
        }

        .gallery-section {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gallery-item {
            position: relative;
            cursor: pointer;
            overflow: hidden;
            border-radius: 8px;
            aspect-ratio: 1;
            background: #e5e7eb;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay i {
            color: white;
            font-size: 2rem;
        }

        #lightboxImage {
            max-height: 70vh;
            object-fit: contain;
        }

        .modal-content {
            border: none;
        }

        hr {
            opacity: 0.1;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .stats-box h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</body>
</html>