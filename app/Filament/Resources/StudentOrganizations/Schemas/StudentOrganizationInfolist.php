<?php

namespace App\Filament\Resources\StudentOrganizations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class StudentOrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Organisasi')
                ->schema([
                    Grid::make(12)->schema([
                        Section::make('Logo & Identitas')
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('logo')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->height(120)
                                    ->alignCenter(),

                                TextEntry::make('name')
                                    ->hiddenLabel()
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->alignCenter(),
                            ]),

                        Section::make('Detail Metadata')
                            ->columnSpan(8)
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('slug')->label('Slug'),
                                    TextEntry::make('parent.name')
                                        ->label('Kategori / Parent')
                                        ->placeholder('Top Level / Mandiri')
                                        ->badge(),

                                    IconEntry::make('is_group')
                                        ->label('Grup Organisasi')
                                        ->boolean(),

                                    IconEntry::make('is_active')
                                        ->label('Status Aktif')
                                        ->boolean(),

                                    TextEntry::make('sort_order')->label('Urutan Tampil'),
                                ]),

                                Section::make('Call To Action')
                                    ->visible(fn($record) => filled($record->cta_url))
                                    ->schema([
                                        TextEntry::make('cta_label')->label('Label Tombol'),
                                        TextEntry::make('cta_url')
                                            ->label('URL Tujuan')
                                            ->url(fn($state) => $state, true)
                                            ->badge(),
                                    ]),
                            ]),
                    ]),
                ]),

            Section::make('Visual & Konten')
                ->schema([
                    ImageEntry::make('cover_image')
                        ->label('Cover Image')
                        ->disk('public')
                        ->width('100%')
                        ->height(300)
                        ->visible(fn($record) => filled($record->cover_image)),

                    Section::make('Konten / Deskripsi')
                        ->schema([
                            TextEntry::make('content')
                                ->hiddenLabel()
                                ->html()
                                ->placeholder('Tidak ada konten deskripsi.'),
                        ]),
                ]),
        ]);
    }
}
