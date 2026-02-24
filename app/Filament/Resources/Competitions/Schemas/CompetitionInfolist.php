<?php

namespace App\Filament\Resources\Competitions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompetitionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Detail Kompetisi')
                ->schema([
                    Grid::make(12)->schema([
                        Section::make('Identitas')
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('cover_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->height(150),

                                TextEntry::make('name')
                                    ->label('Nama')
                                    ->weight('bold'),
                            ]),

                        Section::make('Metadata')
                            ->columnSpan(8)
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('slug'),
                                    TextEntry::make('parent.name')
                                        ->label('Induk')
                                        ->badge()
                                        ->placeholder('Top Level'),

                                    IconEntry::make('is_group')
                                        ->label('Kategori')
                                        ->boolean(),

                                    IconEntry::make('is_active')
                                        ->label('Aktif')
                                        ->boolean(),

                                    TextEntry::make('sort_order')->label('Order'),
                                ]),
                            ]),
                    ]),
                ]),

            Section::make('Link & Konten')
                ->schema([
                    TextEntry::make('url')
                        ->label('URL')
                        ->url(fn($state) => $state, true)
                        ->icon('heroicon-m-link')
                        ->visible(fn($record) => filled($record->url)),

                    TextEntry::make('content')
                        ->label('Konten')
                        ->html()
                        ->placeholder('Tidak ada rincian konten.'),
                ]),
        ]);
    }
}
