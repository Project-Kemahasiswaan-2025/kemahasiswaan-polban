<?php

namespace App\Filament\Resources\ProfilePages;

use App\Filament\Resources\ProfilePages\Pages\CreateProfilePage;
use App\Filament\Resources\ProfilePages\Pages\EditProfilePage;
use App\Filament\Resources\ProfilePages\Pages\ListProfilePages;
use App\Filament\Resources\ProfilePages\Pages\ViewProfilePage;
use App\Filament\Resources\ProfilePages\Schemas\ProfilePageForm;
use App\Filament\Resources\ProfilePages\Schemas\ProfilePageInfolist;
use App\Filament\Resources\ProfilePages\Tables\ProfilePagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfilePageResource extends Resource
{
    protected static ?string $model = Page::class;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_profile_pages');
    }

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('section', 'profile');
    }

    public static function form(Schema $schema): Schema
    {
        return ProfilePageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProfilePageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfilePagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfilePages::route('/'),
            'create' => CreateProfilePage::route('/create'),
            'view' => ViewProfilePage::route('/{record}'),
            'edit' => EditProfilePage::route('/{record}/edit'),
        ];
    }
}
