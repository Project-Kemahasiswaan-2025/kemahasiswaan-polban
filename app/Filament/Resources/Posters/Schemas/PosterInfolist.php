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
            Section::make(__('filament.sections.poster'))
                ->schema([
                    ImageEntry::make('image_path')
                        ->label(__('filament.sections.poster'))
                        ->disk('public')
                        ->height(300),

                    TextEntry::make('title')->label(__('filament.fields.title')),
                    TextEntry::make('category.name')->label(__('filament.fields.category'))->placeholder('-'),
                    TextEntry::make('created_at')->label(__('filament.fields.created_at_label'))->date('d M Y')->placeholder('-'),
                    IconEntry::make('is_active')->label(__('filament.fields.is_active'))->boolean(),
                ]),
        ]);
    }
}
