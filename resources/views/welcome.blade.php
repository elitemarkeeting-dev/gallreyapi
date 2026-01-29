<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-5">All Gallery Images</h1>
        <div id="gallery-container" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <script>
        const API_URL = 'http://gallreyapi.test/api/galleries';
        const API_TOKEN = '2|T2YxHe4BvRWuplzgJ6SUWJYRTWLUmvKAMJHg9jZ77ce6c71f';

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
                    `<div class="alert alert-danger">Error loading galleries: ${error.message}</div>`;
            }
        }

        function displayGalleries(galleries) {
            const container = document.getElementById('gallery-container');
            
            // Collect all images from all galleries
            const allImages = [];
            galleries.forEach(gallery => {
                if (gallery.images && gallery.images.length > 0) {
                    gallery.images.forEach(image => {
                        allImages.push({
                            url: image.full_path || image.url || image.image_path,
                            alt_text: image.alt_text || gallery.name,
                            gallery_name: gallery.name
                        });
                    });
                }
            });

            if (allImages.length === 0) {
                container.innerHTML = '<p class="text-muted">No images found.</p>';
                return;
            }

            console.log('Images found:', allImages); // Debug log
            container.className = 'row g-4';
            container.innerHTML = allImages.map(image => `
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <img src="${image.url}" class="card-img-top" alt="${image.alt_text}" style="height:250px;object-fit:cover;">
                        <div class="card-body">
                            <p class="card-text"><small class="text-muted">From: ${image.gallery_name}</small></p>
                            <p class="card-text">${image.alt_text}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        fetchGalleries();
    </script>
    <style>
        .hover-shadow { transition: all 0.3s; }
        .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    </style>
</body>
</html>