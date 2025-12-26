<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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
                            ->columnSpan(10),

                        TextEntry::make('sort_order')
                            ->label('Urutan')
                            ->columnSpan(2),
                    ]),

                    Section::make('Link')
                        ->schema([
                            Grid::make(12)->schema([
                                TextEntry::make('url')
                                    ->label('URL')
                                    ->placeholder('-')
                                    ->url(fn ($record) => filled($record->url) ? $record->url : null)
                                    ->openUrlInNewTab()
                                    ->columnSpan(8),

                                TextEntry::make('url_target')
                                    ->label('Target')
                                    ->placeholder('-')
                                    ->badge()
                                    ->columnSpan(4),
                            ]),
                        ])
                        ->collapsed(),

                    Section::make('Publish')
                        ->schema([
                            Grid::make(12)->schema([
                                IconEntry::make('is_active')
                                    ->label('Aktif')
                                    ->boolean()
                                    ->columnSpan(3),

                                IconEntry::make('is_pinned')
                                    ->label('Pin')
                                    ->boolean()
                                    ->columnSpan(3),

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
                        ])
                        ->collapsed(),
                ])
                ->columns(1),
        ]);
    }
}
