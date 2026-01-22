<?php

namespace App\Filament\Resources\ProfilePages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProfilePageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi')
                ->columns(2)
                ->schema([
                    TextEntry::make('title')
                        ->label('Title'),

                    TextEntry::make('slug')
                        ->label('Slug'),

                    TextEntry::make('language.name')
                        ->label('Bahasa')
                        ->placeholder('Default'),

                    TextEntry::make('sort_order')
                        ->label('Order'),

                    IconEntry::make('is_active')
                        ->label('Aktif')
                        ->boolean(),
                ]),

            Section::make('Dokumen / Media')
                ->schema([
                    // Preview untuk image saja
                    ImageEntry::make('document_path')
                        ->label('Preview')
                        ->disk('public')
                        ->visible(fn($record) => filled($record?->document_path) && ! Str::endsWith(strtolower($record->document_path), '.pdf'))
                        ->height(240),

                    // Link untuk PDF
                    TextEntry::make('document_path')
                        ->label('PDF')
                        ->visible(fn($record) => filled($record?->document_path) && Str::endsWith(strtolower($record->document_path), '.pdf'))
                        ->state(fn($record) => basename((string) $record->document_path))
                        ->url(fn($record) => asset('storage/' . $record->document_path), true)
                        ->badge(),

                    // Fallback kalau kosong
                    TextEntry::make('document_path')
                        ->label('Dokumen')
                        ->placeholder('Tidak ada')
                        ->visible(fn($record) => blank($record?->document_path)),
                ]),

            Section::make('Konten')
                ->schema([
                    TextEntry::make('content')
                        ->label('Konten')
                        ->html()
                        ->placeholder('Tidak ada konten.'),
                ]),
        ]);
    }
}
