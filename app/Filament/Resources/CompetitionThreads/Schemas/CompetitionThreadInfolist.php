<?php

namespace App\Filament\Resources\CompetitionThreads\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class CompetitionThreadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.thread_info'))
                ->schema([
                    Grid::make(12)->schema([
                        Section::make(__('filament.sections.visual'))
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('custom_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->visible(fn($record) => $record?->visual_type === 'manual'),

                                ImageEntry::make('poster.image_path')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->visible(fn($record) => $record?->visual_type === 'poster'),
                            ]),

                        Section::make(__('filament.sections.metadata'))
                            ->columnSpan(8)
                            ->schema([
                                TextEntry::make('title')
                                    ->label(__('filament.fields.title'))
                                    ->weight('bold')
                                    ->size(TextSize::Large),

                                Grid::make(2)->schema([
                                    TextEntry::make('competition.name')
                                        ->label(__('filament.fields.catalog_item'))
                                        ->badge(),

                                    TextEntry::make('slug'),

                                    IconEntry::make('is_active')
                                        ->label(__('filament.fields.is_active'))
                                        ->boolean(),

                                    IconEntry::make('is_featured')
                                        ->label('Featured')
                                        ->boolean(),
                                ]),
                            ]),
                    ]),
                ]),

            Section::make(__('filament.sections.schedule_timeline'))
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('registration_start')
                            ->label(__('filament.fields.registration_start'))
                            ->date(),

                        TextEntry::make('registration_end')
                            ->label(__('filament.fields.registration_end'))
                            ->date()
                            ->color(fn($record) => $record?->registration_end && $record->registration_end->isPast() ? 'danger' : 'success'),
                    ]),

                    RepeatableEntry::make('timeline')
                        ->schema([
                            TextEntry::make('label')->label(__('filament.fields.timeline_label')),
                            TextEntry::make('date')->label(__('filament.fields.date'))->date(),
                        ])
                        ->columns(2),
                ]),

            Section::make(__('filament.sections.detail_content'))
                ->schema([
                    TextEntry::make('content')
                        ->hiddenLabel()
                        ->html(),
                ]),

            Section::make(__('filament.sections.announcement_result'))
                ->visible(fn($record) => filled($record?->announcement_content))
                ->schema([
                    TextEntry::make('announcement_content')
                        ->hiddenLabel()
                        ->html(),
                ]),
        ]);
    }
}
