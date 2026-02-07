<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Service;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Utama')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label('Nama Layanan')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(Service::class, 'slug', ignoreRecord: true),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(4),

                        TextInput::make('icon')
                            ->label('Icon (Bootstrap Icon Class)')
                            ->placeholder('bi-gear')
                            ->columnSpan(8),
                    ]),

                    TextInput::make('excerpt')
                        ->label('Ringkasan')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Konten Halaman')
                        ->columnSpanFull(),
                ]),

            Section::make('Link Eksternal / Action')
                ->description('Gunakan ini jika layanan ini memiliki tombol aksi langsung atau redirect ke luar.')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('cta_label')
                            ->label('Label Tombol')
                            ->columnSpan(4),

                        TextInput::make('cta_url')
                            ->label('URL Aksi/Redirect')
                            ->url()
                            ->columnSpan(8),
                    ]),
                ])
                ->collapsible(),

            Section::make('Sub-Layanan (Anak)')
                ->description('Inputkan sub-layanan langsung di sini.')
                ->schema([
                    Repeater::make('children')
                        ->relationship('children')
                        ->schema([
                            Grid::make(12)->schema([
                                TextInput::make('name')
                                    ->label('Nama Sub-Layanan')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug((string) $state)))
                                    ->columnSpan(8),
                                TextInput::make('slug')
                                    ->required()
                                    ->columnSpan(4),
                            ]),
                            TextInput::make('cta_url')
                                ->label('URL Redirect / Dokumen')
                                ->url(),
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ])
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                ])
                ->collapsible(),

            Section::make('Dokumen & Unduhan')
                ->description('Kelola dokumen yang dapat diunduh pada halaman layanan ini.')
                ->schema([
                    Repeater::make('downloads')
                        ->relationship('downloads')
                        ->schema([
                            Grid::make(12)->schema([
                                TextInput::make('name')
                                    ->label('Nama Dokumen')
                                    ->required()
                                    ->columnSpan(8),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->columnSpan(4),
                            ]),
                            FileUpload::make('file_path')
                                ->label('File')
                                ->required()
                                ->disk('public')
                                ->directory('downloads/services')
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        // You could potentially extract file size/type here if needed
                                    }
                                }),
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ])
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                ])
                ->collapsible(),
        ]);
    }
}
