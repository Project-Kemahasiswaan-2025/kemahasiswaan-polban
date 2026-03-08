<?php

namespace App\Filament\Resources\RunningTexts;

use App\Filament\Resources\RunningTexts\Pages\ListRunningTexts;
use App\Filament\Resources\RunningTexts\Schemas\RunningTextForm;
use App\Filament\Resources\RunningTexts\Tables\RunningTextsTable;
use App\Models\RunningText;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RunningTextResource extends Resource
{
    protected static ?string $model = RunningText::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;

    // protected static ?string $recordTitleAttribute = 'content';

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group_home');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_running_texts');
    }

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return RunningTextForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RunningTextsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRunningTexts::route('/'),
        ];
    }
}
