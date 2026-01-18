<?php

namespace App\Filament\Resources\RunningTexts;

use App\Filament\Resources\RunningTexts\Pages\CreateRunningText;
use App\Filament\Resources\RunningTexts\Pages\EditRunningText;
use App\Filament\Resources\RunningTexts\Pages\ListRunningTexts;
use App\Models\Language;
use App\Models\RunningText;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
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

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Running Texts';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Running Text')
                ->schema([
                    Select::make('language_id')
                        ->label('Bahasa')
                        ->options(Language::active()->pluck('name', 'id'))
                        ->default(fn () => activeLanguage()?->id)
                        ->required()
                        ->native(false)
                        ->columnSpanFull(),

                    Textarea::make('content')
                        ->label('Content')
                        ->required()
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),

                    Grid::make(3)->schema([
                        TextInput::make('duration_ms')
                            ->label('Duration (ms)')
                            ->numeric()
                            ->default(8000)
                            ->minValue(1000)
                            ->maxValue(60000)
                            ->required()
                            ->helperText('Display duration in milliseconds'),

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

                TextColumn::make('language.icon')
                    ->label('')
                    ->size(TextSize::Large),

                TextColumn::make('language.name')
                    ->label('Language')
                    ->badge()
                    ->sortable(),

                TextColumn::make('duration_ms')
                    ->label('Duration (ms)')
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
                \Filament\Tables\Filters\SelectFilter::make('language_id')
                    ->label('Language')
                    ->relationship('language', 'name')
                    ->preload(),
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
