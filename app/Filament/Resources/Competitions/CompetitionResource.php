<?php

namespace App\Filament\Resources\Competitions;

use App\Filament\Resources\Competitions\Pages\CreateCompetition;
use App\Filament\Resources\Competitions\Pages\EditCompetition;
use App\Filament\Resources\Competitions\Pages\ListCompetitions;
use App\Filament\Resources\Competitions\Pages\ViewCompetition;
use App\Models\Competition;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function getNavigationGroup(): ?string
    {
        return 'Prestasi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Katalog Kompetisi';
    }

    protected static ?int $navigationSort = 40;

    public static function getLabel(): string
    {
        return 'Kompetisi';
    }

    public static function getPluralLabel(): string
    {
        return 'Kompetisi';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return Schemas\CompetitionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Schemas\CompetitionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\CompetitionsTable::configure($table)
            ->modifyQueryUsing(function ($query) {
                $parentId = data_get(request()->query('tableFilters', []), 'parent_id.value');

                return $query->when(
                    filled($parentId),
                    fn($q) => $q->where('parent_id', (int) $parentId),
                    fn($q) => $q->whereNull('parent_id'),
                );
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCompetitions::route('/'),
            'create' => CreateCompetition::route('/create'),
            'view'   => ViewCompetition::route('/{record}'),
            'edit'   => EditCompetition::route('/{record}/edit'),
        ];
    }
}
