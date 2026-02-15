<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Row 1 — Informasi utama + CTA
            Section::make('Informasi Layanan')
                ->description('Ringkasan metadata layanan dan tombol aksi (CTA) bila mengarah ke halaman eksternal.')
                ->schema([
                    Grid::make(12)->schema([
                        // Kiri
                        Section::make('Metadata')
                            ->columnSpan(6)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Layanan')
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->columnSpanFull(),

                                Grid::make(12)->schema([
                                    TextEntry::make('slug')
                                        ->label('Slug')
                                        ->copyable()
                                        ->columnSpan(8),

                                    TextEntry::make('sort_order')
                                        ->label('Urutan')
                                        ->columnSpan(4),
                                ]),

                                Grid::make(12)->schema([
                                    IconEntry::make('is_active')
                                        ->label('Aktif')
                                        ->boolean()
                                        ->columnSpan(4),

                                    TextEntry::make('icon')
                                        ->label('Icon')
                                        ->html()
                                        ->state(function ($record) {
                                            $icon = (string) ($record?->icon ?? '');

                                            if ($icon === '') {
                                                return '<span class="text-muted">-</span>';
                                            }

                                            // tampilkan icon + class name biar kebaca
                                            return sprintf(
                                                '<span class="d-inline-flex align-items-center gap-2">
                                                    <i class="bi %s" style="font-size: 1.25rem;"></i>
                                                    <b class="text-muted">(%s)</b>
                                                </span>',
                                                e($icon),
                                                e($icon),
                                            );
                                        })
                                        ->columnSpan(8),
                                ]),
                            ]),

                        // Kanan
                        Section::make('Ringkasan & CTA')
                            ->columnSpan(6)
                            ->schema([
                                TextEntry::make('excerpt')
                                    ->label('Ringkasan')
                                    ->placeholder('Tidak ada ringkasan.')
                                    ->columnSpanFull(),

                                Section::make('Tombol Aksi')
                                    ->description('Ditampilkan jika layanan memiliki redirect / aksi ke halaman eksternal.')
                                    ->visible(fn($record) => filled($record?->cta_url) && filled($record?->cta_label))
                                    ->schema([
                                        Grid::make(12)->schema([
                                            TextEntry::make('cta_label')
                                                ->label('Label Tombol')
                                                ->columnSpan(4),

                                            TextEntry::make('cta_url')
                                                ->label('URL Aksi / Redirect')
                                                ->url(fn($state) => $state, true)
                                                ->openUrlInNewTab()
                                                ->copyable()
                                                ->columnSpan(8),
                                        ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ]),
                ])
                ->columnSpanFull(),

            // Row 2 — Konten
            Section::make('Konten Halaman')
                ->columnSpanFull()
                ->visible(function ($record): bool {
                    $raw = (string) ($record?->content ?? '');
                    $text = trim(strip_tags($raw));
                    $text = trim(preg_replace('/\xc2\xa0|&nbsp;/', ' ', $text));
                    return $text !== '';
                })
                ->schema([
                    TextEntry::make('content')
                        ->hiddenLabel()
                        ->html()
                        ->formatStateUsing(function ($state) {
                            $html = (string) ($state ?? '');

                            // Pastikan img center (mirip issue kamu sebelumnya)
                            return preg_replace_callback(
                                '/<img\b[^>]*>/i',
                                static function ($m) {
                                    $tag = $m[0];

                                    if (preg_match('/\sstyle=("|\')(.*?)\1/i', $tag, $sm)) {
                                        $quote = $sm[1];
                                        $style = rtrim($sm[2], '; ') . '; display:block; margin-left:auto; margin-right:auto;';
                                        return preg_replace('/\sstyle=("|\')(.*?)\1/i', ' style=' . $quote . $style . $quote, $tag, 1);
                                    }

                                    return preg_replace(
                                        '/<img\b/i',
                                        '<img style="display:block;margin-left:auto;margin-right:auto;"',
                                        $tag,
                                        1
                                    );
                                },
                                $html
                            );
                        })
                        ->placeholder('Tidak ada konten.'),
                ]),

            // Row 3 — Tautan lanjutan + Unduhan
            Section::make('Tautan Lanjutan')
                ->description('Daftar tautan terkait untuk melanjutkan ke halaman/portal eksternal yang masih berhubungan dengan layanan ini.')
                ->collapsible()
                ->visible(fn($record) => $record?->children?->isNotEmpty())
                ->schema([
                    RepeatableEntry::make('children')
                        ->label('Tautan')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Judul')
                                ->weight('bold'),

                            TextEntry::make('cta_url')
                                ->label('URL Tujuan')
                                ->url(fn($state) => $state, true)
                                ->openUrlInNewTab()
                                ->copyable()
                                ->badge(),
                        ])
                        ->columns(1),
                ]),

            Section::make('Dokumen & Unduhan')
                ->description('Dokumen yang tersedia untuk dibuka/diunduh.')
                ->collapsible()
                ->visible(fn($record) => $record?->downloads?->isNotEmpty())
                ->schema([
                    RepeatableEntry::make('downloads')
                        ->label('Dokumen')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nama')
                                ->weight('bold'),

                            TextEntry::make('file_path')
                                ->label('File')
                                ->state(fn($record) => basename((string) $record->file_path))
                                ->url(fn($record) => asset('storage/' . $record->file_path), true)
                                ->openUrlInNewTab()
                                ->badge(),
                        ])
                        ->columns(1),
                ]),
        ]);
    }
}
