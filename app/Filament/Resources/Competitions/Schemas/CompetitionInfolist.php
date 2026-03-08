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
            Section::make(__('filament.sections.competition_detail'))
                ->schema([
                    Grid::make(12)->schema([
                        Section::make(__('filament.sections.identity'))
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('cover_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->height(150),

                                TextEntry::make('name')
                                    ->label(__('filament.fields.name'))
                                    ->weight('bold'),
                            ]),

                        Section::make(__('filament.sections.metadata'))
                            ->columnSpan(8)
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('slug'),
                                    TextEntry::make('parent.name')
                                        ->label(__('filament.fields.parent'))
                                        ->badge()
                                        ->placeholder(__('filament.placeholders.top_level')),

                                    IconEntry::make('is_group')
                                        ->label(__('filament.fields.category'))
                                        ->boolean(),

                                    IconEntry::make('is_active')
                                        ->label(__('filament.fields.is_active'))
                                        ->boolean(),

                                    TextEntry::make('sort_order')->label('Order'),
                                ]),
                            ]),
                    ]),
                ]),

            Section::make(__('filament.sections.link_and_content'))
                ->schema([
                    TextEntry::make('url')
                        ->label(__('filament.fields.url'))
                        ->url(fn($state) => $state, true)
                        ->icon('heroicon-m-link')
                        ->visible(fn($record) => filled($record->url)),

                    TextEntry::make('content')
                        ->label(__('filament.fields.content'))
                        ->html()
                        ->placeholder(__('filament.placeholders.no_content')),
                ]),
        ]);
    }
}
