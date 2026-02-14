<?php

namespace App\Filament\Resources\RunningTexts;

use App\Filament\Resources\RunningTexts\Pages\CreateRunningText;
use App\Filament\Resources\RunningTexts\Pages\EditRunningText;
use App\Filament\Resources\RunningTexts\Pages\ListRunningTexts;
use App\Models\RunningText;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RunningTextResource extends Resource
{
    protected static ?string $model = RunningText::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSpeakerWave;

    protected static ?string $recordTitleAttribute = 'content';

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
        return $schema->schema([
            Section::make('Running Text')
                ->schema([
                    Textarea::make('content')
                        ->label('Content')
                        ->required()
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),

                    Grid::make(3)->schema([
                        TextInput::make('duration_seconds')
                            ->label('Duration (seconds)')
                            ->numeric()
                            ->default(8)
                            ->minValue(1)
                            ->maxValue(60)
                            ->required()
                            ->helperText('Display duration in seconds'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content')
                    ->label('Content')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('duration_seconds')
                    ->label('Duration (s)')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRunningTexts::route('/'),
            'create' => CreateRunningText::route('/create'),
            'edit' => EditRunningText::route('/{record}/edit'),
        ];
    }
}
