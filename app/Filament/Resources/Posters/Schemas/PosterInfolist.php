<?php

namespace App\Filament\Resources\Posters\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PosterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Poster')
                ->schema([
                    ImageEntry::make('image_path')
                        ->label('Poster')
                        ->disk('public')
                        ->height(300),

                    TextEntry::make('title')->label('Judul'),
                    TextEntry::make('category.name')->label('Kategori')->placeholder('-'),
                    TextEntry::make('published_at')->label('Publish')->date('d M Y')->placeholder('-'),
                    IconEntry::make('is_active')->label('Aktif')->boolean(),
                ]),
        ]);
    }
}
