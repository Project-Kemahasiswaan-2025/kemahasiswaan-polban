<?php

namespace App\Filament\Resources\StudentOrganizations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentOrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('parent.name')
                    ->label('Parent')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('node_type'),
                TextEntry::make('category')
                    ->placeholder('-'),
                TextEntry::make('excerpt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('cover_image')
                    ->placeholder('-'),
                TextEntry::make('logo')
                    ->placeholder('-'),
                TextEntry::make('cta_label')
                    ->placeholder('-'),
                TextEntry::make('cta_url')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
