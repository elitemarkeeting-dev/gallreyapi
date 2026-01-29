# Gallrey API - Image Gallery Management System

A modern Laravel 12 application for managing and sharing image galleries with a responsive Tailwind CSS interface and read-only REST API.

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Authentication](#authentication)
- [Web Routes](#web-routes)
- [API Documentation](#api-documentation)
- [CSV Export](#csv-export)
- [Dashboard Features](#dashboard-features)
- [File Structure](#file-structure)

---

## ✨ Features

### 🎨 Frontend
- **Tailwind CSS** - Modern, responsive design with gradient themes
- **Mobile Responsive** - Works seamlessly on mobile, tablet, and desktop
- **Drag & Drop Upload** - Intuitive multi-image upload with live preview
- **Gallery Management** - Create, edit, and delete galleries
- **Image Management** - Add, view, and remove images from galleries
- **Bulk Upload** - Upload multiple images at once
- **Live Image Preview** - See images before confirming upload

### 🔐 Authentication & Authorization
- **Session-based Authentication** - Simple login/logout without npm dependencies
- **Role-based Access** - Admin, User, and Moderator roles
- **Gallery Policies** - Only owners can edit/delete their galleries
- **Protected Routes** - Dashboard and gallery management require authentication

### 📊 Dashboard
- **User Statistics** - View count of galleries and images
- **Recent Galleries** - Display last 6 galleries in grid
- **Quick Actions** - Create gallery or export CSV
- **User Profile** - Display name, username, email, and role

### 📤 Data Export
- **CSV Export** - Download all galleries data to CSV file
- **Formatted Data** - Includes Gallery ID, Name, Owner, Image Count, etc.

### 📡 API
- **Read-Only REST API** - No authentication required
- **Gallery Listing** - Paginated gallery data with images
- **Gallery Details** - Full gallery information including images
- **Image Endpoints** - Direct access to gallery images
- **JSON Response** - Consistent API responses with metadata

---

## 🛠 Technology Stack

| Component | Version |
|-----------|---------|
| **Laravel Framework** | 12.49.0 |
| **PHP** | 8.4.16 |
| **MySQL** | Latest (via Laravel Herd) |
| **Tailwind CSS** | v4 (CDN) |
| **Laravel Sanctum** | v4 |
| **Pest** | v4 |
| **Laravel Pint** | v1 |

---

## 📦 Installation

### Prerequisites
- PHP 8.4+
- Laravel Herd (for local development)
- Composer

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/ADC212006/gallreyapi.git
   cd gallreyapi
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database** (if needed)
   ```bash
   # Update .env with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gallreyapi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database**
   ```bash
   php artisan db:seed
   ```

8. **Access the application**
   ```
   http://gallreyapi.test
   ```

---

## 🔑 Authentication

### Test Credentials

Three pre-seeded users are available:

| Email | Password | Username | Role |
|-------|----------|----------|------|
| admin@example.com | password | admin | Admin |
| user@example.com | password | testuser | User |
| moderator@example.com | password | moderator | Moderator |

### Login
Navigate to `/login` and enter your credentials.

### Logout
Click the "Logout" button in the dashboard or navigation.

---

## 🗺 Web Routes

### Public Routes
```
GET  /                    - Welcome page
GET  /login               - Login page
GET  /galleries           - Browse all galleries (public)
GET  /gallery/{id}        - View specific gallery
```

### Protected Routes (Authenticated Users)
```
GET  /dashboard           - User dashboard
GET  /gallery/create      - Create gallery form
POST /gallery             - Store new gallery
GET  /gallery/{id}/edit   - Edit gallery form
PUT  /gallery/{id}        - Update gallery
DELETE /gallery/{id}      - Delete gallery
GET  /galleries/export    - Download galleries as CSV
DELETE /gallery-image/{id} - Delete single image
POST /logout              - Logout user
```

---

## 📡 API Documentation

### Base URL
```
http://gallreyapi.test/api
```

### Authentication
All API endpoints are **public and read-only**. No authentication token required.

### Response Format
All endpoints return JSON with consistent format:
```json
{
  "success": true,
  "data": [...],
  "pagination": {...}
}
```

---

### 1. Get All Galleries (Paginated)
**Endpoint:** `GET /api/galleries`

**Parameters:**
- `page` (optional) - Page number (default: 1)
- `per_page` (optional) - Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Summer Vacation",
      "slug": "summer-vacation",
      "description": "Beautiful beach photos",
      "user_id": 1,
      "created_at": "2026-01-29T10:00:00Z",
      "updated_at": "2026-01-29T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Example:**
```bash
curl http://gallreyapi.test/api/galleries
curl http://gallreyapi.test/api/galleries?page=2
```

---

### 2. Get Gallery Details
**Endpoint:** `GET /api/galleries/{id}`

**Parameters:**
- `id` (required) - Gallery ID

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Summer Vacation",
    "slug": "summer-vacation",
    "description": "Beautiful beach photos",
    "created_at": "2026-01-29T10:00:00Z",
    "updated_at": "2026-01-29T10:00:00Z",
    "owner": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "images": [
      {
        "id": 1,
        "url": "http://gallreyapi.test/storage/galleries/1/image1.jpg",
        "alt_text": "Summer Vacation",
        "position": 0
      }
    ],
    "images_count": 10
  }
}
```

**Example:**
```bash
curl http://gallreyapi.test/api/galleries/1
```

---

### 3. Get Gallery Images Only
**Endpoint:** `GET /api/galleries/{id}/images`

**Parameters:**
- `id` (required) - Gallery ID

**Response:**
```json
{
  "success": true,
  "gallery": {
    "id": 1,
    "name": "Summer Vacation",
    "slug": "summer-vacation"
  },
  "data": [
    {
      "id": 1,
      "url": "http://gallreyapi.test/storage/galleries/1/image1.jpg",
      "full_path": "http://gallreyapi.test/storage/galleries/1/image1.jpg",
      "alt_text": "Summer Vacation",
      "position": 0,
      "created_at": "2026-01-29T10:00:00Z"
    }
  ],
  "total": 10
}
```

**Example:**
```bash
curl http://gallreyapi.test/api/galleries/1/images
```

---

## 📊 CSV Export

### Download Galleries as CSV
**Endpoint:** `GET /galleries/export`

**Requirements:**
- User must be authenticated

**Response:**
- CSV file with columns: Gallery ID, Name, Slug, Description, Owner, Images Count, Created At
- Filename: `galleries-YYYY-MM-DD.csv`

**Example:**
```bash
curl -H "Cookie: laravel-session=..." http://gallreyapi.test/galleries/export
```

---

## 📈 Dashboard Features

### Statistics Cards
- **My Galleries** - Count of user's galleries
- **Total Images** - Total images across all user galleries
- **Role** - Display current user role

### Quick Actions
- Create new gallery
- Export all galleries to CSV

### Recent Galleries Preview
- Grid display of last 6 galleries
- Thumbnail from first image
- Image count per gallery
- Link to view full gallery

### Empty State
- Helpful message when no galleries exist
- Quick link to create first gallery

---

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php          # Base controller with AuthorizesRequests
│   │   ├── GalleryController.php   # Web gallery CRUD
│   │   ├── Auth/
│   │   │   └── LoginController.php # Authentication
│   │   └── Api/
│   │       └── GalleryApiController.php # API endpoints
│   └── Policies/
│       └── GalleryPolicy.php       # Gallery authorization
├── Models/
│   ├── User.php                    # User model with role relationship
│   ├── Role.php                    # Role model
│   ├── Gallery.php                 # Gallery model
│   └── GalleryImage.php            # Gallery image model
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_01_29_080821_create_personal_access_tokens_table.php
│   ├── 2026_01_29_081400_create_roles_table.php
│   ├── 2026_01_29_081429_add_username_and_role_to_users_table.php
│   ├── 2026_01_29_082850_create_galleries_table.php
│   └── 2026_01_29_082855_create_gallery_images_table.php
├── factories/
│   └── UserFactory.php
└── seeders/
    └── RoleAndUserSeeder.php       # Pre-seed roles and test users

resources/
└── views/
    ├── auth/
    │   └── login.blade.php         # Login page (Tailwind CSS)
    ├── dashboard.blade.php         # User dashboard
    ├── welcome.blade.php           # Welcome page
    └── gallery/
        ├── index.blade.php         # Gallery listing (public)
        ├── show.blade.php          # Gallery details
        ├── create.blade.php        # Create gallery form
        └── edit.blade.php          # Edit gallery form

routes/
├── web.php                         # Web routes
├── api.php                         # API routes
└── console.php

storage/
└── app/
    └── public/
        └── galleries/              # Gallery images storage
```

---

## 🎨 Styling

### Tailwind CSS Features
- **Gradient Background** - Purple to Indigo gradient throughout
- **Responsive Grid** - 1 column mobile, 2 columns tablet, 3+ columns desktop
- **Hover Effects** - Scale and shadow transitions
- **Dark Mode Ready** - Color scheme works on light backgrounds
- **Mobile Navigation** - Collapsible menu for small screens
- **Icons** - SVG icons from Tailwind CSS

### Color Scheme
- **Primary:** Purple (600-700) to Indigo (600-700)
- **Success:** Green (600)
- **Danger:** Red (600)
- **Background:** Gray (50)

---

## 🔒 Security Features

### Authorization
- **Gallery Policies** - Only owners can edit/delete galleries
- **Protected Routes** - Dashboard and management routes require authentication
- **CSRF Protection** - All forms include CSRF tokens
- **Input Validation** - All user inputs validated on server

### API
- **Read-Only** - No modification endpoints available
- **No Authentication Required** - Public access for display
- **Safe Response** - Sensitive data excluded from API

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test --filter=testName
```

### Generate Coverage
```bash
php artisan test --coverage
```

---

## 🔧 Development Commands

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Reset database
php artisan db:seed              # Seed database
php artisan tinker               # Interactive shell
```

### Artisan
```bash
php artisan list                 # List all commands
php artisan route:list           # List all routes
php artisan make:model Model     # Generate model
php artisan make:migration name  # Generate migration
```

### Code Quality
```bash
vendor/bin/pint --dirty          # Format changed files
vendor/bin/pint                  # Format all files
```

---

## 📝 Models & Relationships

### User Model
```php
- hasMany: Gallery
- belongsTo: Role
- Properties: id, name, username, email, password, role_id
```

### Role Model
```php
- hasMany: User
- Properties: id, name, description
```

### Gallery Model
```php
- belongsTo: User
- hasMany: GalleryImage
- Properties: id, name, slug, description, user_id
```

### GalleryImage Model
```php
- belongsTo: Gallery
- Properties: id, gallery_id, image_path, alt_text, position
```

---

## 🐛 Troubleshooting

### 500 Error on Gallery Creation
**Issue:** `Call to undefined method authorize()`
**Solution:** Ensure `AuthorizesRequests` trait is in base Controller

### Images Not Displaying
**Issue:** Images stored but not visible
**Solution:** Run `php artisan storage:link` to create public storage symlink

### Database Seeding Error
**Issue:** Duplicate role entries
**Solution:** Run `php artisan migrate:fresh --seed` to reset and reseed

### Permission Denied on Git Push
**Issue:** GitHub access denied
**Solution:** 
- Use SSH: `git remote set-url origin git@github.com:ADC212006/gallreyapi.git`
- Or request repository collaborator access

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Pest Testing](https://pestphp.com)

---

## 📄 License

This project is licensed under the MIT License.

---

## 👥 Contributors

- Initial development and setup

---

## 📞 Support

For issues or questions, please create an issue in the repository.

---

**Last Updated:** January 29, 2026  
**Version:** 1.0.0
