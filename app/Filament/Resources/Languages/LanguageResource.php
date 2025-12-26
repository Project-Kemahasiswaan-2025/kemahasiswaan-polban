<?php

namespace App\Filament\Resources\Languages;

use App\Filament\Resources\Languages\Pages\CreateLanguage;
use App\Filament\Resources\Languages\Pages\EditLanguage;
use App\Filament\Resources\Languages\Pages\ListLanguages;
use App\Models\Language;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Language Details')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., id, en'),

                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Bahasa Indonesia, English'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('icon')
                            ->label('Icon')
                            ->maxLength(10)
                            ->placeholder('🇮🇩 or 🇬🇧')
                            ->nullable(),

                        Grid::make(2)->schema([
                            Toggle::make('is_default')
                                ->label('Default Language')
                                ->default(false),

                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ]),
                    ]),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label('')
                    ->size(TextSize::Large),

                TextColumn::make('name')
                    ->label('Language')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('is_default', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_default')->label('Default'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->before(function ($records) {
                        // Prevent deletion of default language
                        $defaultLanguage = $records->where('is_default', true)->first();
                        if ($defaultLanguage) {
                            throw new \Exception('Cannot delete the default language.');
                        }

                        // Ensure at least one active language remains
                        $activeCount = Language::active()->count();
                        $deletingActiveCount = $records->where('is_active', true)->count();
                        if ($activeCount - $deletingActiveCount < 1) {
                            throw new \Exception('At least one active language must remain.');
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLanguages::route('/'),
            'create' => CreateLanguage::route('/create'),
            'edit' => EditLanguage::route('/{record}/edit'),
        ];
    }
}
