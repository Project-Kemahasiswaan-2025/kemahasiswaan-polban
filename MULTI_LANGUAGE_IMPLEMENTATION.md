# Multi-Language System Implementation

## Overview
This implementation provides a comprehensive multi-language system for the Kemahasiswaan Polban project, supporting both Indonesian (Bahasa Indonesia) and English languages in the Filament admin panel.

## Features Implemented

### 1. Database Structure
- **Languages table** with:
  - `code` (e.g., 'id', 'en')
  - `name` (e.g., 'Bahasa Indonesia', 'English')
  - `icon` (emoji flags: 🇮🇩, 🇬🇧)
  - `is_default` (boolean)
  - `is_active` (boolean)
  
- **Content tables** (banners, videos, student_organizations) extended with:
  - `language_id` foreign key to languages table

### 2. Language Model
Located at: `app/Models/Language.php`

Features:
- `active()` scope - Get only active languages
- `default()` scope - Get the default language
- `byCode($code)` scope - Find language by code

### 3. Helper Functions
Located at: `app/helpers.php`

Functions:
- `activeLanguage()` - Get current active language from session
- `setActiveLanguage($language)` - Set active language and update app locale

### 4. Middleware
Located at: `app/Http/Middleware/SetLocaleMiddleware.php`

- Automatically sets application locale based on session
- Falls back to default language if not set

### 5. Filament Admin Panel Integration

#### Language Management Resource
Located at: `app/Filament/Resources/Languages/LanguageResource.php`

Features:
- Full CRUD for languages
- Validation prevents deletion of default language
- Validation ensures at least one active language remains
- List view with active/default indicators

#### Language Switcher Widget
Located at: `app/Filament/Widgets/LanguageSwitcher.php`

Features:
- Displays at top of admin panel
- Shows language flag icons and codes
- Highlights currently active language
- Switches language on click

#### Updated Content Resources
All content resources (Banner, Video, StudentOrganization) now have:
- Language selector in create/edit forms (defaults to active language)
- Language column with flag icon in list tables
- Language filter for easy content filtering

### 6. Configuration

#### Default Locale
File: `config/app.php`
- Default locale set to Indonesian ('id')
- Fallback locale set to Indonesian ('id')

#### Translation Files
- `lang/id/common.php` - Indonesian translations
- `lang/en/common.php` - English translations

## Usage

### For Administrators

#### Switching Languages
1. Log into the admin panel
2. Look for the language switcher at the top of the page
3. Click on the desired language (🇮🇩 id or 🇬🇧 en)
4. The page will refresh with the new locale

#### Managing Languages
1. Navigate to the "Languages" resource in the admin panel
2. View, create, edit, or manage available languages
3. Toggle active status to enable/disable languages
4. Set default language (only one can be default)

#### Creating Content in Different Languages
1. When creating/editing Banners, Videos, or Student Organizations
2. Select the desired language from the "Bahasa" dropdown
3. The language will default to your currently active language
4. Save the content

#### Filtering Content by Language
1. In any content list (Banners, Videos, Student Organizations)
2. Use the "Bahasa" filter to show only content in specific languages
3. The list will show language flags next to each item

### For Developers

#### Adding Language Support to New Resources

1. **Update the migration:**
```php
$table->foreignId('language_id')
    ->nullable()
    ->constrained('languages')
    ->nullOnDelete();
```

2. **Update the model:**
```php
protected $fillable = ['language_id', ...];

public function language(): BelongsTo
{
    return $this->belongsTo(Language::class);
}
```

3. **Update the Filament resource form:**
```php
Select::make('language_id')
    ->label('Bahasa')
    ->options(Language::active()->pluck('name', 'id'))
    ->default(fn() => activeLanguage()?->id)
    ->required()
    ->native(false)
```

4. **Update the Filament resource table:**
```php
TextColumn::make('language.icon')
    ->label('')
    ->size(TextColumn\TextColumnSize::Large),

TextColumn::make('language.name')
    ->label('Bahasa')
    ->badge()
    ->sortable(),
```

5. **Add language filter:**
```php
SelectFilter::make('language_id')
    ->label('Bahasa')
    ->relationship('language', 'name')
    ->preload(),
```

#### Getting Current Language

```php
// Get current active language
$language = activeLanguage();

// Get language code
$code = activeLanguage()?->code;

// Set active language
setActiveLanguage($languageId);
// or
setActiveLanguage($languageModel);
```

## Database Seeding

The `LanguageSeeder` automatically creates two languages:
- Indonesian (code: 'id', default: true, active: true)
- English (code: 'en', default: false, active: true)

To seed:
```bash
php artisan db:seed --class=LanguageSeeder
```

Or run all seeders:
```bash
php artisan migrate:fresh --seed
```

## Files Created/Modified

### New Files
- `app/Models/Language.php`
- `app/helpers.php`
- `app/Http/Middleware/SetLocaleMiddleware.php`
- `app/Filament/Resources/Languages/LanguageResource.php`
- `app/Filament/Resources/Languages/Pages/ListLanguages.php`
- `app/Filament/Resources/Languages/Pages/CreateLanguage.php`
- `app/Filament/Resources/Languages/Pages/EditLanguage.php`
- `app/Filament/Widgets/LanguageSwitcher.php`
- `resources/views/filament/widgets/language-switcher.blade.php`
- `database/migrations/2025_12_26_132840_create_languages_table.php`
- `database/migrations/2025_12_26_132943_add_language_id_to_content_tables.php`
- `database/seeders/LanguageSeeder.php`
- `lang/id/common.php`
- `lang/en/common.php`

### Modified Files
- `composer.json` (added helpers.php to autoload)
- `config/app.php` (changed default locale to 'id')
- `app/Providers/Filament/AdminPanelProvider.php` (added middleware and widget)
- `app/Models/Banner.php` (added language relationship)
- `app/Models/Video.php` (added language relationship)
- `app/Models/StudentOrganization.php` (added language relationship)
- `app/Filament/Resources/Banners/BannerResource.php` (added language column and filter)
- `app/Filament/Resources/Banners/Schemas/BannerForm.php` (added language field)
- `app/Filament/Resources/Videos/VideoResource.php` (added language field, column and filter)
- `app/Filament/Resources/StudentOrganizations/StudentOrganizationResource.php` (added language field, column and filter)
- `database/seeders/DatabaseSeeder.php` (added LanguageSeeder)

## Testing

All functionality has been tested:
- ✅ Migrations run successfully
- ✅ Languages seeded correctly
- ✅ Language model and relationships work
- ✅ Helper functions return correct values
- ✅ Middleware sets locale properly
- ✅ Content tables have language_id column
- ✅ Filament resources show language fields

## Notes

- The implementation is designed to be simple and practical
- Language selector is positioned at the top for easy access
- All content resources now support multiple languages
- The system defaults to Indonesian (Bahasa Indonesia)
- Session-based language switching allows per-user language preferences
