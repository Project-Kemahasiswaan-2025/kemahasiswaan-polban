<?php

namespace App\Filament\Resources\StudentOrganizations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class StudentOrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.org_info'))
                ->schema([
                    Grid::make(12)->schema([
                        Section::make(__('filament.sections.logo_identity'))
                            ->columnSpan(4)
                            ->schema([
                                ImageEntry::make('logo')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->height(120)
                                    ->alignCenter(),

                                TextEntry::make('name')
                                    ->hiddenLabel()
                                    ->weight('bold')
                                    ->size(TextSize::Large)
                                    ->alignCenter(),
                            ]),

                        Section::make(__('filament.sections.detail_metadata'))
                            ->columnSpan(8)
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('slug')->label('Slug'),
                                    TextEntry::make('parent.name')
                                        ->label(__('filament.fields.category'))
                                        ->placeholder(__('filament.placeholders.top_level_independent'))
                                        ->badge(),

                                    IconEntry::make('is_group')
                                        ->label(__('filament.fields.is_group'))
                                        ->boolean(),

                                    IconEntry::make('is_active')
                                        ->label(__('filament.fields.is_active'))
                                        ->boolean(),

                                    TextEntry::make('sort_order')->label(__('filament.fields.sort_order')),
                                ]),

                            ]),
                    ]),
                ]),

            Section::make(__('filament.sections.visual_content'))
                ->schema([
                    ImageEntry::make('cover_image')
                        ->label('Cover Image')
                        ->disk('public')
                        ->width('100%')
                        ->height(300)
                        ->visible(fn($record) => filled($record->cover_image)),

                    Section::make(__('filament.sections.content_description'))
                        ->schema([
                            TextEntry::make('content')
                                ->hiddenLabel()
                                ->html()
                                ->placeholder(__('filament.placeholders.no_content_desc')),
                        ]),
                ]),
        ]);
    }
}
