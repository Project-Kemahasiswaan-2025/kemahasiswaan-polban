<?php

namespace App\Filament\Resources\Competitions\Schemas;

use App\Models\Competition;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CompetitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Utama')
                ->description('Metadata dasar untuk katalog kompetisi.')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label('Nama Kompetisi / Kategori')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) return;
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(Competition::class, 'slug', ignoreRecord: true),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(4),

                        Toggle::make('is_group')
                            ->label('Grup / Kategori')
                            ->helperText('Aktifkan jika ini adalah pengelompokan (seperti Puspresnas, Bakorma, dll).')
                            ->default(false)
                            ->live()
                            ->columnSpan(8),
                    ]),

                    Select::make('parent_id')
                        ->label('Induk Kategori')
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query
                                ->whereNull('parent_id')
                                ->where('is_group', true)
                                ->orderBy('sort_order')
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->disabled(fn($get) => (bool) $get('is_group'))
                        ->helperText('Kosongkan jika ini adalah kategori tingkat atas.'),

                    FileUpload::make('cover_image')
                        ->label('Gambar Sampul (Opsional)')
                        ->disk('public')
                        ->directory('competitions/covers')
                        ->image()
                        ->imageEditor()
                        ->nullable(),
                ]),

            Section::make('Tautan & Deskripsi')
                ->description('Informasi tambahan jika kompetisi memiliki halaman luar atau detail khusus.')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('url')
                            ->label('URL Eksternal')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpan(8)
                            ->disabled(fn($get) => (bool) $get('is_group')),

                        Select::make('url_target')
                            ->label('Target Link')
                            ->options([
                                '_blank' => 'Tab Baru',
                                '_self'  => 'Tab Sama',
                            ])
                            ->default('_blank')
                            ->columnSpan(4)
                            ->disabled(fn($get) => (bool) $get('is_group')),
                    ]),

                    RichEditor::make('content')
                        ->label('Detail Konten')
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->collapsible(),
        ]);
    }
}
