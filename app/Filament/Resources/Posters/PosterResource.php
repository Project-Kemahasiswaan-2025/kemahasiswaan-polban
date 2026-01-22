<?php

namespace App\Filament\Resources\Posters;

use App\Filament\Resources\Posters\Pages\CreatePoster;
use App\Filament\Resources\Posters\Pages\EditPoster;
use App\Filament\Resources\Posters\Pages\ListPosters;
use App\Filament\Resources\Posters\Pages\ViewPoster;
use App\Filament\Resources\Posters\Schemas\PosterForm;
use App\Filament\Resources\Posters\Schemas\PosterInfolist;
use App\Filament\Resources\Posters\Tables\PostersTable;
use App\Models\Poster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PosterResource extends Resource
{
    protected static ?string $model = Poster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Beranda';
    // protected static ?string $navigationParentItem = 'Poster'; // optional
    protected static ?string $navigationLabel = 'Poster';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getLabel(): string
    {
        return 'Poster';
    }

    public static function getPluralLabel(): string
    {
        return 'Poster';
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PosterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PosterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosters::route('/'),
            'create' => CreatePoster::route('/create'),
            'view' => ViewPoster::route('/{record}'),
            'edit' => EditPoster::route('/{record}/edit'),
        ];
    }
}
