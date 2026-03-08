<?php

namespace App\Filament\Resources\Downloads\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                            ->live(onBlur: true)
                            ->columnSpan(8),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(4),
                    ]),

                    Select::make('category_id')
                        ->label('Kategori (Section di Landing Page)')
                        ->relationship('category', 'name', fn($query) => $query->where('type', 'download'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih section untuk landing page...'),

                    FileUpload::make('file_path')
                        ->label('File Dokumen')
                        ->required()
                        ->disk('public')
                        ->directory('downloads/general')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (!$get('name') && $state) {
                                $file = is_array($state) ? array_first($state) : $state;
                                if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                    $set('name', (string) str($filename)->replace(['-', '_'], ' ')->title());
                                }
                            }
                        }),
                ]),
        ]);
    }
}
