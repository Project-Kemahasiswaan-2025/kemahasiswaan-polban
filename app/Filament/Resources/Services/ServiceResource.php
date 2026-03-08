<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Pages\ViewService;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Schemas\ServiceInfolist;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_services');
    }

    protected static ?int $navigationSort = 20;

    public static function getLabel(): string
    {
        return __('filament.resources.service.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.resources.service.plural_label');
    }

    // protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordUrlAttribute = 'slug';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'view' => ViewService::route('/{record}'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
