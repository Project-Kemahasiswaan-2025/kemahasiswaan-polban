<?php

namespace App\Filament\Resources\PosterCategories;

use App\Filament\Resources\PosterCategories\Pages\ListPosterCategories;
use App\Filament\Resources\PosterCategories\Schemas\PosterCategoryForm;
use App\Filament\Resources\PosterCategories\Schemas\PosterCategoryInfolist;
use App\Filament\Resources\PosterCategories\Tables\PosterCategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PosterCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group_master_categories');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_poster_categories');
    }

    protected static ?int $navigationSort = 101;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('type', 'poster');
    }

    public static function getLabel(): string
    {
        return 'Kategori Poster';
    }

    public static function getPluralLabel(): string
    {
        return 'Kategori Poster';
    }

    public static function form(Schema $schema): Schema
    {
        return PosterCategoryForm::configure($schema);
    }

    // public static function infolist(Schema $schema): Schema
    // {
    //     return PosterCat::configure($schema);
    // }

    public static function table(Table $table): Table
    {
        return PosterCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosterCategories::route('/'),
        ];
    }
}
