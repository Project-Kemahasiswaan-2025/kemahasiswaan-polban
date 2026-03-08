<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class BannerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.banner'))
                ->schema([
                    ImageEntry::make('image_path')
                        ->label(__('filament.fields.image'))
                        ->disk('public')
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        TextEntry::make('title')
                            ->label(__('filament.fields.title'))
                            ->placeholder('-')
                            ->columnSpan(10),

                        TextEntry::make('sort_order')
                            ->label(__('filament.fields.sort_order'))
                            ->placeholder('-')
                            ->columnSpan(2),
                    ]),

                    Grid::make(12)->schema([
                        TextEntry::make('url')
                            ->label(__('filament.fields.url_optional'))
                            ->placeholder('-')
                            ->state(function ($record) {
                                if (blank($record->url)) {
                                    return null;
                                }

                                $suffix = match ($record->url_target) {
                                    '_blank' => __('filament.options.tab_new_suffix'),
                                    '_self'  => __('filament.options.tab_same_suffix'),
                                    default  => '',
                                };

                                return $record->url . $suffix;
                            })
                            ->url(fn($record) => filled($record->url) ? $record->url : null)
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
                    ]),

                    Grid::make(12)->schema([
                        Text::make('')
                            ->view('components.hr-divider', [
                                'label' => __('filament.sections.display_settings'),
                                'icon'  => 'fas fa-cogs',
                            ])
                            ->columnSpanFull(),
                    ]),

                    Grid::make(6)->schema([
                        TextEntry::make('active_from')
                            ->label(__('filament.fields.active_from'))
                            ->dateTime('d M Y H:i')
                            ->placeholder('-')
                            ->columnSpan(3),

                        TextEntry::make('active_to')
                            ->label(__('filament.fields.active_to'))
                            ->dateTime('d M Y H:i')
                            ->placeholder('-')
                            ->columnSpan(3),
                    ]),

                    Grid::make(4)->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean()
                            ->columnSpan(1),

                        IconEntry::make('is_pinned')
                            ->label(__('filament.fields.is_pinned'))
                            ->boolean()
                            ->columnSpan(1),
                    ]),
                ])
                ->columns(1),
        ]);
    }
}
