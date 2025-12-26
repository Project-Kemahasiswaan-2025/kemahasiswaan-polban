<?php

namespace App\Filament\Resources\Banners;

use App\Filament\Resources\Banners\Pages\CreateBanner;
use App\Filament\Resources\Banners\Pages\EditBanner;
use App\Filament\Resources\Banners\Pages\ListBanners;
use App\Filament\Resources\Banners\Pages\ViewBanner;
use App\Filament\Resources\Banners\Schemas\BannerInfolist;
use App\Models\Banner;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Banner')
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Gambar')
                        ->disk('public')
                        ->directory('banners')
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->maxLength(150)
                            ->required()
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    Fieldset::make('Link (Opsional)')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('url')
                                    ->label('URL')
                                    ->placeholder('https://...')
                                    ->url()
                                    ->maxLength(255)
                                    ->nullable()
                                    ->live(),

                                Select::make('url_target')
                                    ->label('Target')
                                    ->options([
                                        '_self' => 'Tab yang sama',
                                        '_blank' => 'Tab baru',
                                    ])
                                    ->default('_self'),
                            ]),
                        ])
                        ->columns(1),

                    Fieldset::make('Publish')
                        ->schema([
                            Grid::make(4)->schema([
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),

                                Toggle::make('is_pinned')
                                    ->label('Pin')
                                    ->default(false),

                                DateTimePicker::make('active_from')
                                    ->label('Aktif Mulai')
                                    ->seconds(false)
                                    ->nullable(),

                                DateTimePicker::make('active_to')
                                    ->label('Aktif Sampai')
                                    ->seconds(false)
                                    ->nullable(),
                            ]),
                        ])
                        ->columns(1),
                ])
                ->columns(1),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BannerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->square(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('language.icon')
                    ->label('')
                    ->size(TextSize::Large),

                TextColumn::make('language.name')
                    ->label('Bahasa')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_pinned')
                    ->label('Pin')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('active_from')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('active_to')
                    ->label('Sampai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_pinned')->label('Pin'),
                \Filament\Tables\Filters\SelectFilter::make('language_id')
                    ->label('Bahasa')
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
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'view' => ViewBanner::route('/{record}'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }
}
