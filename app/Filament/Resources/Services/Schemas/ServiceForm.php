<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Forms\Components\BootstrapIconPicker;
use App\Models\Service;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
            // Row 1
            Section::make('Informasi Layanan')
                ->description('Metadata utama layanan, termasuk tombol aksi (CTA) bila layanan mengarah ke halaman eksternal.')
                ->schema([
                    Grid::make(12)->schema([
                        // KIRI
                        Grid::make(12)
                            ->columnSpan(6)
                            ->schema([
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
                                    ->columnSpanFull(),

                                Grid::make(12)->schema([
                                    TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->unique(Service::class, 'slug', ignoreRecord: true)
                                        ->columnSpan(9),

                                    TextInput::make('sort_order')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0)
                                        ->columnSpan(3),
                                ])->columnSpanFull(),

                                BootstrapIconPicker::make('icon')
                                    ->label('Icon')
                                    ->icons(config('bootstrap-icons'))
                                    ->required()
                                    ->columnSpanFull(),

                            ]),

                        // KANAN
                        Grid::make(12)
                            ->columnSpan(6)
                            ->schema([
                                TextInput::make('excerpt')
                                    ->label('Ringkasan')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Section::make('Tombol Aksi')
                                    ->description('Opsional. Gunakan bila halaman layanan ini butuh tombol untuk menuju URL eksternal atau aksi tertentu.')
                                    ->schema([
                                        Grid::make(12)->schema([
                                            TextInput::make('cta_label')
                                                ->label('Label Tombol')
                                                ->columnSpan(4),

                                            TextInput::make('cta_url')
                                                ->label('URL Aksi / Redirect')
                                                ->url()
                                                ->columnSpan(8),
                                        ]),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),
                    ]),
                ])
                ->columnSpanFull(),

            // Row 2
            Section::make('Konten Halaman')
                ->schema([
                    RichEditor::make('content')
                        ->hiddenLabel()
                        ->columnSpanFull(),
                ])->columnSpanFull(),

            // Row 3
            Section::make('Tautan Lanjutan')
                ->description('Daftar tautan terkait untuk melanjutkan ke halaman/portal eksternal yang masih berhubungan dengan layanan ini.')
                ->schema([
                    Repeater::make('links')
                        ->label('Tautan')
                        ->relationship('links')
                        ->schema([
                            TextInput::make('name')
                                ->label('Judul Tautan')
                                ->required(),
                            TextInput::make('url')
                                ->label('URL Tujuan')
                                ->url()
                                ->required(),
                            TextInput::make('description')
                                ->label('Keterangan Singkat')
                                ->placeholder('Opsional'),
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

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(4),
                            ]),

                            FileUpload::make('file_path')
                                ->label('File')
                                ->required()
                                ->disk('public')
                                ->directory('downloads/services'),
                        ])
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                ])
                ->collapsible(),
        ]);
    }
}
