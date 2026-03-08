<?php

namespace App\Filament\Resources\ProfilePages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProfilePageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(12)->schema([
                Section::make(__('filament.sections.information'))
                    ->columnSpan(7)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('slug')->label('Slug'),
                        TextEntry::make('sort_order')->label('Order'),

                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                    ]),

                Section::make(__('filament.sections.document_media'))
                    ->columnSpan(5)
                    ->schema([
                        ImageEntry::make('document_path')
                            ->label(__('filament.fields.document_preview'))
                            ->disk('public')
                            ->visible(
                                fn($record) => filled($record?->document_path)
                                    && ! Str::endsWith(strtolower($record->document_path), '.pdf')
                            )
                            ->height(240),

                        TextEntry::make('document_path')
                            ->label(__('filament.fields.pdf'))
                            ->visible(
                                fn($record) => filled($record?->document_path)
                                    && Str::endsWith(strtolower($record->document_path), '.pdf')
                            )
                            ->state(fn($record) => basename((string) $record->document_path))
                            ->url(fn($record) => asset('storage/' . $record->document_path), true)
                            ->badge(),

                        TextEntry::make('document_path')
                            ->label('Dokumen')
                            ->placeholder('Tidak ada')
                            ->visible(fn($record) => blank($record?->document_path)),
                    ]),

                Section::make(__('filament.sections.content'))
                    ->columnSpanFull()
                    ->visible(function ($record): bool {
                        $raw = (string) ($record?->content ?? '');
                        $text = trim(preg_replace('/\xc2\xa0|&nbsp;/', ' ', strip_tags($raw))); // treat &nbsp; as empty
                        return $text !== '';
                    })
                    ->schema([
                        TextEntry::make('content')
                            ->hiddenLabel()
                            ->html()
                            ->formatStateUsing(function ($state) {
                                $html = (string) ($state ?? '');

                                $html = preg_replace_callback(
                                    '/<img\b[^>]*>/i',
                                    static function ($m) {
                                        $tag = $m[0];

                                        if (preg_match('/\sstyle=("|\')(.*?)\1/i', $tag, $sm)) {
                                            $quote = $sm[1];
                                            $style = rtrim($sm[2], '; ') . '; display:block; margin-left:auto; margin-right:auto;';
                                            return preg_replace('/\sstyle=("|\')(.*?)\1/i', ' style=' . $quote . $style . $quote, $tag, 1);
                                        }

                                        return preg_replace(
                                            '/<img\b/i',
                                            '<img style="display:block;margin-left:auto;margin-right:auto;"',
                                            $tag,
                                            1
                                        );
                                    },
                                    $html
                                );

                                return $html;
                            })
                            ->placeholder(__('filament.placeholders.no_content')),
                    ]),
            ])->columnSpanFull(),
        ]);
    }
}
