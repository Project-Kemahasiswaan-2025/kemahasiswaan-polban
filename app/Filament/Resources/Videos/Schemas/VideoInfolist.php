<?php

namespace App\Filament\Resources\VideoResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Preview')
                ->schema([
                    TextEntry::make('youtube_id')
                        ->label('')
                        ->html()
                        ->formatStateUsing(function ($state) {
                            if (!$state) return '<div style="opacity:.6">Belum ada video.</div>';

                            $id = e($state);

                            return <<<HTML
<div style="aspect-ratio:16/9;width:100%;border-radius:12px;overflow:hidden;">
  <iframe
    src="https://www.youtube.com/embed/{$id}"
    style="width:100%;height:100%;border:0;"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
    allowfullscreen
  ></iframe>
</div>
HTML;
                        }),

                    TextEntry::make('title')->label('Judul'),
                    TextEntry::make('youtube_url')->label('YouTube URL')->url(fn($record) => $record->youtube_url)->openUrlInNewTab(),
                ]),
        ]);
    }
}
