<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MorphToSelect;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Dokumen')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label('Nama Dokumen')
                            ->required()
                            ->columnSpan(8),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(4),
                    ]),

                    \Filament\Forms\Components\Select::make('category_id')
                        ->label('Kategori (Section di Landing Page)')
                        ->relationship('category', 'name', fn($query) => $query->where('type', 'download'))
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih section untuk landing page...'),

                    FileUpload::make('file_path')
                        ->label('File Dokumen')
                        ->required()
                        ->disk('public')
                        ->directory('downloads/general')
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                // Potentially extract info if needed
                            }
                        }),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ]),

            Section::make('Relasi / Terkait Dengan')
                ->schema([
                    MorphToSelect::make('downloadable')
                        ->label('Tautkan ke Entitas')
                        ->types([
                            MorphToSelect\Type::make(\App\Models\Service::class)
                                ->titleAttribute('name')
                                ->label('Layanan'),
                            // You can add more types here if needed
                        ])
                        ->searchable()
                        ->preload(),
                ])
                ->collapsible(),
        ]);
    }
}
