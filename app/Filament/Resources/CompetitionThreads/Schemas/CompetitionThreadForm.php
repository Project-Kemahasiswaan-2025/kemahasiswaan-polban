<?php

namespace App\Filament\Resources\CompetitionThreads\Schemas;

use App\Models\Competition;
use App\Models\CompetitionThread;
use App\Models\Poster;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CompetitionThreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('Thread Kompetisi')
                ->tabs([
                    Tabs\Tab::make('Informasi Umum')
                        ->icon('heroicon-o-identification')
                        ->schema(static::identityTab()),

                    Tabs\Tab::make('Visual')
                        ->icon('heroicon-o-photo')
                        ->schema(static::visualTab()),

                    Tabs\Tab::make('Link & Timeline')
                        ->icon('heroicon-o-calendar-days')
                        ->schema(static::linkTimelineTab()),
                ])
                ->persistTabInQueryString()
                ->columnSpanFull(),
        ]);
    }

    // ─── Tab 1: Informasi Umum ──────────────────────────────────────

    protected static function identityTab(): array
    {
        return [
            Grid::make(12)->schema([
                Select::make('competition_id')
                    ->label('Item Katalog')
                    ->relationship(
                        name: 'competition',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query
                            ->where('is_group', false)
                            ->orderBy('name')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hintAction(
                        Action::make('create_item')
                            ->icon('heroicon-m-plus')
                            ->url(\App\Filament\Resources\Competitions\CompetitionResource::getUrl('create'), true)
                    )
                    ->columnSpan(8),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft'               => 'Draft',
                        'ongoing'             => 'Sedang Berlangsung',
                        'registration_closed' => 'Pendaftaran Ditutup',
                        'completed'           => 'Selesai',
                    ])
                    ->default('draft')
                    ->required()
                    ->columnSpan(4),
            ]),

            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label('Judul Pengumuman')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set, $get) => $set('slug', Str::slug((string) $state)))
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(CompetitionThread::class, 'slug', ignoreRecord: true),
            ]),

            RichEditor::make('content')
                ->label('Detail Deskripsi Lomba')
                ->columnSpanFull(),

            Section::make('Pengumuman Hasil (Juara)')
                ->description('Opsional. Isi jika lomba sudah selesai dan ada pengumuman pemenang.')
                ->schema([
                    RichEditor::make('announcement_content')
                        ->label('Konten Pengumuman')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }

    // ─── Tab 2: Visual / Poster ───────────────────────────────────

    protected static function visualTab(): array
    {
        return [
            Select::make('poster_id')
                ->label('Pilih Poster dari Galeri')
                ->relationship(
                    name: 'poster',
                    titleAttribute: 'title',
                    modifyQueryUsing: fn($query) => $query->orderBy('created_at', 'desc')
                )
                ->searchable()
                ->preload()
                ->nullable()
                ->live()
                ->helperText('Opsional. Pilih poster yang sudah ada di galeri sebagai visual thread.'),

            Placeholder::make('poster_preview')
                ->label('Preview Poster')
                ->content(function ($get) {
                    $posterId = $get('poster_id');
                    if (! $posterId) return null;

                    $poster = Poster::find($posterId);
                    if (! $poster || ! $poster->image_path) return 'Poster tidak ditemukan.';

                    $url = asset('storage/' . $poster->image_path);
                    return new HtmlString(
                        '<div style="display:inline-block;max-width:240px;">'
                            . '<img src="' . e($url) . '" alt="Preview" style="width:100%;height:auto;border-radius:8px;border:1px solid #e5e7eb;object-fit:contain;">'
                            . '</div>'
                    );
                })
                ->visible(fn($get) => filled($get('poster_id'))),

            FileUpload::make('custom_image')
                ->label('Atau Upload Gambar')
                ->helperText('Gambar ini hanya dipakai untuk thread ini, tidak masuk ke galeri poster.')
                ->disk('public')
                ->directory('competition-threads/images')
                ->image()
                ->nullable(),
        ];
    }

    // ─── Tab 3: Link & Timeline ───────────────────────────────────

    protected static function linkTimelineTab(): array
    {
        return [
            Section::make('Link')
                ->description('Disarankan untuk mengisi setidaknya satu link sebagai CTA utama.')
                ->schema([
                    TextInput::make('post_url')
                        ->label('Link Postingan / Info Lengkap')
                        ->placeholder('https://...')
                        ->url()
                        ->maxLength(500)
                        ->nullable()
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('registration_url')
                            ->label('Link Pendaftaran')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        TextInput::make('guidebook_url')
                            ->label('Link Guidebook / Juknis')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('contact_info')
                            ->label('Kontak (WA / Email)')
                            ->placeholder('08xxxxxxxxxx / email@example.com')
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('location')
                            ->label('Lokasi')
                            ->placeholder('Gedung A, Kampus Utama')
                            ->maxLength(255)
                            ->nullable(),
                    ]),
                ]),

            Section::make('Jadwal')
                ->description('Isi periode pendaftaran dan tahapan penting lainnya.')
                ->schema([
                    Grid::make(2)->schema([
                        DatePicker::make('registration_start')
                            ->label('Pendaftaran Dibuka')
                            ->native(false),

                        DatePicker::make('registration_end')
                            ->label('Pendaftaran Ditutup')
                            ->native(false),
                    ]),

                    Repeater::make('timelines')
                        ->label('Timeline Tahapan')
                        ->relationship()
                        ->schema([
                            TextInput::make('label')
                                ->label('Nama Tahapan')
                                ->required(),
                            DatePicker::make('date')
                                ->label('Tanggal')
                                ->native(false)
                                ->required(),
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->reorderable()
                        ->orderColumn('sort_order')
                        ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->addActionLabel('Tambah Tahapan'),
                ]),
        ];
    }
}
