<?php

namespace App\Filament\Resources\CompetitionThreads;

use App\Filament\Resources\CompetitionThreads\Pages\ManageCompetitionThreads;
use App\Models\CompetitionThread;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetitionThreadResource extends Resource
{
    protected static ?string $model = CompetitionThread::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.resources.competition_thread.nav_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.competition_thread.nav_label');
    }

    protected static ?int $navigationSort = 41;

    public static function getLabel(): string
    {
        return __('filament.resources.competition_thread.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.resources.competition_thread.plural_label');
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return Schemas\CompetitionThreadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Schemas\CompetitionThreadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\CompetitionThreadsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCompetitionThreads::route('/'),
            'create' => Pages\CreateCompetitionThread::route('/create'),
            'view'   => Pages\ViewCompetitionThread::route('/{record}'),
            'edit'   => Pages\EditCompetitionThread::route('/{record}/edit'),
        ];
    }
}
