<?php

namespace App\Filament\Resources\VideoCategories;

use App\Filament\Resources\VideoCategories\Pages\ListVideoCategories;
use App\Filament\Resources\VideoCategories\Schemas\VideoCategoryForm;
use App\Filament\Resources\VideoCategories\Tables\VideoCategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group_master_categories');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_video_categories');
    }

    protected static ?int $navigationSort = 103;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('type', 'video');
    }

    public static function getLabel(): string
    {
        return __('filament.resources.video_category.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.resources.video_category.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return VideoCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoCategories::route('/'),
        ];
    }
}
