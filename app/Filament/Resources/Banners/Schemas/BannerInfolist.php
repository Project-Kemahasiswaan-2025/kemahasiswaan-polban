<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class BannerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Banner')
                ->schema([
                    ImageEntry::make('image_path')
                        ->label('Gambar')
                        ->disk('public')
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        TextEntry::make('title')
                            ->label('Judul')
                            ->placeholder('-')
                            ->columnSpan(10),

                        TextEntry::make('sort_order')
                            ->label('Urutan')
                            ->placeholder('-')
                            ->columnSpan(2),
                    ]),

                    Grid::make(12)->schema([
                        TextEntry::make('url')
                            ->label('Link (opsional)')
                            ->placeholder('-')
                            ->state(function ($record) {
                                if (blank($record->url)) {
                                    return null;
                                }

                                $suffix = match ($record->url_target) {
                                    '_blank' => ' (Tab Baru)',
                                    '_self'  => ' (Tab yang sama)',
                                    default  => '',
                                };

                                return $record->url . $suffix;
                            })
                            ->url(fn($record) => filled($record->url) ? $record->url : null)
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
                    ]),

                    Grid::make(12)->schema([
                        Text::make('')
                            ->view('components.hr-divider', [
                                'label' => 'Pengaturan Tampilan',
                                'icon'  => 'fas fa-cogs',
                            ])
                            ->columnSpanFull(),
                    ]),

                    Grid::make(6)->schema([
                        TextEntry::make('active_from')
                            ->label('Aktif Mulai')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-')
                            ->columnSpan(3),

                        TextEntry::make('active_to')
                            ->label('Aktif Sampai')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-')
                            ->columnSpan(3),
                    ]),

                    Grid::make(4)->schema([
                        IconEntry::make('is_active')
                            ->label('Aktif')
                            ->boolean()
                            ->columnSpan(1),

                        IconEntry::make('is_pinned')
                            ->label('Pin')
                            ->boolean()
                            ->columnSpan(1),
                    ]),
                ])
                ->columns(1),
        ]);
    }
}
