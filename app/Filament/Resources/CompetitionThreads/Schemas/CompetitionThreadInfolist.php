<?php

namespace App\Filament\Resources\CompetitionThreads\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class CompetitionThreadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Thread')
                ->schema([
                    Grid::make(12)->schema([
                        Section::make('Visual')
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('custom_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->visible(fn($record) => $record?->visual_type === 'manual'),

                                ImageEntry::make('poster.image_path')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->visible(fn($record) => $record?->visual_type === 'poster'),
                            ]),

                        Section::make('Metadata')
                            ->columnSpan(8)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Judul')
                                    ->weight('bold')
                                    ->size(TextSize::Large),

                                Grid::make(2)->schema([
                                    TextEntry::make('competition.name')
                                        ->label('Katalog')
                                        ->badge(),

                                    TextEntry::make('slug'),

                                    IconEntry::make('is_active')
                                        ->label('Aktif')
                                        ->boolean(),

                                    IconEntry::make('is_featured')
                                        ->label('Featured')
                                        ->boolean(),
                                ]),
                            ]),
                    ]),
                ]),

            Section::make('Jadwal & Timeline')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('registration_start')
                            ->label('Pendaftaran Dibuka')
                            ->date(),

                        TextEntry::make('registration_end')
                            ->label('Pendaftaran Ditutup')
                            ->date()
                            ->color(fn($record) => $record?->registration_end && $record->registration_end->isPast() ? 'danger' : 'success'),
                    ]),

                    RepeatableEntry::make('timeline')
                        ->schema([
                            TextEntry::make('label')->label('Tahapan'),
                            TextEntry::make('date')->label('Tanggal')->date(),
                        ])
                        ->columns(2),
                ]),

            Section::make('Detail Konten')
                ->schema([
                    TextEntry::make('content')
                        ->hiddenLabel()
                        ->html(),
                ]),

            Section::make('Pengumuman Hasil')
                ->visible(fn($record) => filled($record?->announcement_content))
                ->schema([
                    TextEntry::make('announcement_content')
                        ->hiddenLabel()
                        ->html(),
                ]),
        ]);
    }
}
