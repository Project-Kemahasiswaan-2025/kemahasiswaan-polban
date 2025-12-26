<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
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
                                        '_self'  => 'Tab yang sama',
                                        '_blank' => 'Tab baru',
                                    ])
                                    ->default('_self')
                                    ->visible(fn($get): bool => filled($get('url')))
                                    ->dehydrated(fn($get): bool => filled($get('url'))),
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
}
