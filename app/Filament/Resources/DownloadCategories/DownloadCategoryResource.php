<?php

namespace App\Filament\Resources\DownloadCategories;

use App\Filament\Resources\DownloadCategories\Pages\CreateDownloadCategory;
use App\Filament\Resources\DownloadCategories\Pages\EditDownloadCategory;
use App\Filament\Resources\DownloadCategories\Pages\ListDownloadCategories;
use App\Filament\Resources\DownloadCategories\Schemas\DownloadCategoryForm;
use App\Filament\Resources\DownloadCategories\Tables\DownloadCategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DownloadCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Unduhan';
    protected static ?string $navigationLabel = 'Kategori Unduhan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('type', 'download');
    }

    public static function getLabel(): string
    {
        return 'Kategori Unduhan';
    }

    public static function getPluralLabel(): string
    {
        return 'Kategori Unduhan';
    }

    public static function form(Schema $schema): Schema
    {
        return DownloadCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDownloadCategories::route('/'),
            'create' => CreateDownloadCategory::route('/create'),
            'edit' => EditDownloadCategory::route('/{record}/edit'),
        ];
    }
}
