<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Detail Layanan')
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('slug')->label('Slug'),
                    TextEntry::make('icon')->label('Icon'),
                    TextEntry::make('excerpt')->label('Ringkasan'),
                    TextEntry::make('content')->label('Konten')->html(),
                    TextEntry::make('cta_label')->label('CTA Label'),
                    TextEntry::make('cta_url')
                        ->label('CTA URL')
                        ->url(fn($state) => $state)
                        ->openUrlInNewTab(),
                ]),
        ]);
    }
}
