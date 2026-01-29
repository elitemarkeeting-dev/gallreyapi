# Gallery Layouts Feature

## Overview
Added multiple layout options for galleries with authentication-required API access.

## Available Layouts

### 1. **Grid Layout** (Default)
- Classic responsive grid design
- 1-4 columns based on screen size
- Fixed height images (object-cover)
- Perfect for uniform image display

### 2. **Masonry Layout**
- Pinterest-style cascading columns
- Dynamic height based on image aspect ratio
- 1-4 columns responsive
- Best for varying image sizes

### 3. **List Layout**
- Horizontal card-style layout
- Image on left, details on right
- Large preview with metadata
- Great for showcasing featured images

### 4. **Carousel Layout**
- Full-screen slideshow presentation
- Auto-advance every 5 seconds
- Keyboard navigation (arrow keys)
- Manual controls and indicators
- Perfect for storytelling galleries

## How to Use

### Creating a Gallery
1. Navigate to "Create Gallery"
2. Fill in name and description
3. **Select your preferred layout** from the dropdown
4. Upload images
5. Save

### Changing Layout
1. Edit an existing gallery
2. Change the layout dropdown selection
3. Save changes
4. Layout updates immediately

### Layout Selection Available In:
- Gallery creation form
- Gallery edit form
- Badge displayed in gallery index

## API Authentication

**IMPORTANT:** All gallery API endpoints now require authentication via Laravel Sanctum.

### Endpoints (All require `auth:sanctum`)
```
GET /api/galleries          - List all galleries
GET /api/galleries/{id}     - Single gallery details
GET /api/galleries/{id}/images - Gallery images only
```

### Authentication Required
- Must include Bearer token in request headers
- Unauthenticated requests return 401 Unauthorized
- Use Laravel Sanctum personal access tokens

### Example API Request
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     https://gallreyapi.test/api/galleries
```

## Database Changes

### Migration: `add_layout_to_galleries_table`
- Adds `layout` column (string, default: 'grid')
- Placed after `description` column
- Supports: grid, masonry, list, carousel

## Files Modified

1. **Migration**: `database/migrations/2026_01_29_094345_add_layout_to_galleries_table.php`
2. **Model**: `app/Models/Gallery.php` - Added layout to fillable, defined LAYOUTS constant
3. **Routes**: `routes/api.php` - Added `auth:sanctum` middleware to API routes
4. **Views**:
   - `resources/views/gallery/create.blade.php` - Layout selector
   - `resources/views/gallery/edit.blade.php` - Layout selector
   - `resources/views/gallery/show.blade.php` - All 4 layout implementations
   - `resources/views/gallery/index.blade.php` - Layout badges

## Features Implemented

✅ 4 distinct gallery layouts
✅ Layout selection in create/edit forms
✅ Dynamic layout rendering on gallery show page
✅ Layout badges in gallery index
✅ Carousel with auto-advance and keyboard navigation
✅ Responsive design for all layouts
✅ API authentication requirement
✅ Sanctum middleware protection

## Technical Notes

- Default layout: `grid`
- Carousel JavaScript auto-advances every 5 seconds
- Masonry uses CSS columns (no JavaScript required)
- All layouts support image deletion (owner only)
- Mobile responsive across all layouts
- API returns 401 without valid Sanctum token
