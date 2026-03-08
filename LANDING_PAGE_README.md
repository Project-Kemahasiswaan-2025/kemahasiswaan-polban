# Landing Page Kemahasiswaan Polban - Implementation Guide

## Overview
Implemented a modern, responsive landing page for Direktorat Kemahasiswaan dan Alumni Politeknik Negeri Bandung with dynamic content loading via API.

## Features

### 1. Responsive Design
- **Framework**: Bootstrap 5.3
- **Custom CSS**: CSS variables for theme customization
- **Mobile-First**: Fully responsive across all devices

### 2. Color Scheme
- **Primary**: Navy Blue (#001f3f)
- **Secondary**: Orange (#ff6b35)
- **Implementation**: CSS variables in `/public/css/landing.css`

### 3. Pages

#### Home Page (`/`)
- Banner carousel slider
- Featured videos section
- Quick links to main sections

#### Ormawa Page (`/ormawa`)
- Student organizations listing
- Search functionality
- Category filter (MPM, BEM, HMJ, UKM)
- **Organizations included**:
  - MPM (Majelis Perwakilan Mahasiswa)
  - BEM (Badan Eksekutif Mahasiswa)
  - 10 HMJ (Himpunan Mahasiswa Jurusan)
  - 24 UKM (Unit Kegiatan Mahasiswa)

#### Competition Page (`/kompetisi`)
- Competition listings
- Category filter (Puspresnas BPTI, BAKORMA, Internal Polban)
- External link indicators
- **Competitions included**:
  - 10 Puspresnas BPTI competitions
  - 9 BAKORMA competitions

## API Endpoints

All API endpoints return JSON data and are located under `/api/` prefix.

### 1. Banners API
```
GET /api/banners
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Banner Title",
      "image_url": "/storage/path/to/image.jpg",
      "link": "optional-link",
      "order": 1,
      "is_active": true
    }
  ]
}
```

### 2. Videos API
```
GET /api/videos
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Video Title",
      "video_url": "https://www.youtube.com/watch?v=...",
      "thumbnail": null,
      "is_featured": true
    }
  ]
}
```

### 3. Ormawa API
```
GET /api/ormawa?category=hmj&search=teknik
```
**Query Parameters:**
- `category`: Filter by category (mpm, bem, hmj, ukm)
- `search`: Search by organization name

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "HMJ Teknik Komputer dan Informatika",
      "category": "hmj",
      "description": "Deskripsi singkat",
      "logo": "/storage/path/to/logo.jpg",
      "contact": {
        "email": null,
        "instagram": null
      }
    }
  ]
}
```

### 4. Competitions API
```
GET /api/competitions?category=puspresnas
```
**Query Parameters:**
- `category`: Filter by category (puspresnas, bakorma, internal)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "PKM Belmawa",
      "category": "puspresnas",
      "description": "Deskripsi kompetisi",
      "link": "https://external-link.com",
      "is_external": true,
      "deadline": null,
      "status": "active"
    }
  ]
}
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── BannerController.php
│   │   │   ├── VideoController.php
│   │   │   ├── OrmawaController.php
│   │   │   └── CompetitionController.php
│   │   ├── HomeController.php
│   │   ├── OrmawaController.php
│   │   ├── CompetitionController.php
│   │   └── LanguageController.php
│   └── Middleware/
│       └── SetLocaleMiddleware.php

database/
├── migrations/
│   ├── 2025_12_14_090747_create_banners_table.php
│   ├── 2025_12_24_002813_create_videos_table.php
│   ├── 2025_12_25_230101_create_student_organizations_table.php
│   ├── 2025_12_26_225221_create_competitions_table.php
│   └── 2026_02_07_000000_remove_language_feature.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── BannerSeeder.php
    ├── VideoSeeder.php
    ├── StudentOrganizationSeeder.php
    └── CompetitionSeeder.php

lang/
├── id/
│   ├── app.php
│   ├── menu.php
│   └── common.php
└── en/
    ├── app.php
    ├── menu.php
    └── common.php

public/
├── css/
│   └── landing.css
└── js/
    └── landing.js

resources/
└── views/
    ├── components/
    │   ├── layout/
    │   │   └── app.blade.php
    │   ├── navigation.blade.php
    │   ├── footer.blade.php
    │   ├── banner-slider.blade.php
    │   ├── video-player.blade.php
    │   ├── ormawa-card.blade.php
    │   ├── competition-card.blade.php
    │   ├── search-box.blade.php
    │   └── external-link.blade.php
    └── pages/
        ├── home.blade.php
        ├── ormawa/
        │   └── index.blade.php
        └── competition/
            └── index.blade.php

routes/
├── web.php
└── api.php
```

## Routes

### Web Routes
```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ormawa', [OrmawaController::class, 'index'])->name('ormawa.index');
Route::get('/kompetisi', [CompetitionController::class, 'index'])->name('competition.index');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
```

### API Routes
```php
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/ormawa', [OrmawaController::class, 'index']);
Route::get('/competitions', [CompetitionController::class, 'index']);
```

## Blade Components

### Layout Components
1. **app.blade.php** - Main layout with header, footer, navigation
2. **navigation.blade.php** - Top navigation with dropdown menu
3. **footer.blade.php** - Footer section

### Feature Components
4. **banner-slider.blade.php** - Banner carousel component
5. **video-player.blade.php** - YouTube video embed component
6. **ormawa-card.blade.php** - Card for displaying organizations
7. **competition-card.blade.php** - Card for displaying competitions
9. **search-box.blade.php** - Search component with AJAX
10. **external-link.blade.php** - Icon for external links

## CSS Variables

Located in `/public/css/landing.css`:

```css
:root {
  /* Primary Colors */
  --color-navy: #001f3f;
  --color-navy-light: #0d47a1;
  --color-navy-dark: #001529;
  
  /* Secondary Colors */
  --color-orange: #ff6b35;
  --color-orange-light: #ff8c61;
  --color-orange-dark: #e55a2b;
  
  /* Neutral Colors */
  --color-white: #ffffff;
  --color-black: #000000;
  --color-gray-light: #f5f5f5;
  --color-gray: #888888;
  --color-gray-dark: #333333;
  
  /* Functional Colors */
  --color-primary: var(--color-navy);
  --color-secondary: var(--color-orange);
  --color-accent: var(--color-orange-light);
}
```

## JavaScript Functions

Located in `/public/js/landing.js`:

- `loadBanners()` - Load and render banner carousel
- `loadVideos()` - Load and render featured videos
- `loadOrmawa()` - Load and render organizations with search/filter
- `loadCompetitions()` - Load and render competitions with filter
- `extractYouTubeId(url)` - Extract YouTube video ID from URL

## Database Setup

### Run Migrations and Seeders
```bash
php artisan migrate:fresh --seed
```

This will:
1. Create all required tables
3. Seed sample banners (3 items)
4. Seed sample videos (3 items)
5. Seed student organizations (38 items: MPM, BEM, 10 HMJ, 24 UKM)
6. Seed competitions (21 items: 10 Puspresnas, 9 BAKORMA, 2 categories)

## Development

### Start the Development Server
```bash
php artisan serve
```

### Access the Application
- **Home Page**: http://localhost:8000
- **Ormawa Page**: http://localhost:8000/ormawa
- **Competition Page**: http://localhost:8000/kompetisi

### Test API Endpoints
```bash
curl http://localhost:8000/api/banners
curl http://localhost:8000/api/videos
curl http://localhost:8000/api/ormawa
curl http://localhost:8000/api/competitions
```

## Customization

### Change Color Scheme
Edit `/public/css/landing.css` and modify the CSS variables:
```css
:root {
  --color-navy: #your-primary-color;
  --color-orange: #your-secondary-color;
}
```

### Add New Menu Item
Edit `resources/views/components/navigation.blade.php` and add your menu item with proper translation keys in `lang/{code}/menu.php`.

## Production Deployment

### Build Assets
Since we're using CDN for Bootstrap and jQuery, no build step is required. However, ensure:

1. Custom CSS and JS are minified
2. Image paths are correctly configured for production storage
3. CORS is configured if API will be accessed from different domain

### Environment Variables
Ensure these are properly set in `.env`:
```
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_DATABASE=your_database
```

## Security Notes

1. **Laravel Sanctum** is installed but not yet configured with middleware
2. API endpoints are currently public (no authentication required)
3. CSRF protection is enabled for web routes
4. XSS protection is handled by Blade's `{{ }}` escaping

## Future Enhancements

1. Add authentication to API endpoints using Sanctum
2. Implement pagination for large datasets
3. Add caching layer for API responses
4. Implement real-time updates using Laravel Echo
5. Add admin panel integration for content management
6. Implement image optimization and CDN integration
7. Add analytics tracking
8. Implement search autocomplete
9. Add social media sharing features
10. Implement PWA features for offline support

## Support

For issues or questions, please contact the development team or refer to the Laravel documentation at https://laravel.com/docs
