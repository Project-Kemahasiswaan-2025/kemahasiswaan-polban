<?php

namespace App\Filament\Resources\Competitions\Schemas;

use App\Models\Competition;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CompetitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.main_info'))
                ->description(__('filament.sections.main_info_desc'))
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label(__('filament.fields.competition_name'))
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) return;
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(9),

                        TextInput::make('sort_order')
                            ->label(__('filament.fields.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(3),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(
                            Competition::class,
                            'slug',
                            ignoreRecord: true,
                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, $get) => $rule
                                ->where('parent_id', $get('parent_id'))
                                ->whereNull('deleted_at')
                        ),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->default(true)
                            ->columnSpan(4),

                        Toggle::make('is_group')
                            ->label(__('filament.fields.is_group'))
                            ->helperText(__('filament.fields.is_group_helper'))
                            ->default(false)
                            ->live()
                            ->hidden(fn($get) => (bool) $get('child_mode_enabled') || filled($get('parent_id')))
                            ->columnSpan(8),
                    ]),

                    \Filament\Forms\Components\Hidden::make('child_mode_enabled')
                        ->afterStateHydrated(fn($set, $state) => $set('child_mode_enabled', $state ?? filled(request()->query('parent_id'))))
                        ->dehydrated(false),

                    Select::make('parent_id')
                        ->label(__('filament.fields.parent_category'))
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query
                                ->whereNull('parent_id')
                                ->where('is_group', true)
                                ->orderBy('sort_order')
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->default(fn() => request()->query('parent_id'))
                        ->hidden(fn($get) => (bool) $get('is_group'))
                        ->disabled(fn($get) => (bool) $get('child_mode_enabled'))
                        ->dehydrated()
                        ->helperText(__('filament.fields.parent_category_helper')),

                    \Filament\Forms\Components\Hidden::make('parent_id')
                        ->default(fn() => request()->query('parent_id'))
                        ->visible(fn($get) => (bool) $get('child_mode_enabled')),

                    FileUpload::make('cover_image')
                        ->label(__('filament.fields.cover_image'))
                        ->disk('public')
                        ->directory('competitions/covers')
                        ->image()
                        ->imageEditor()
                        ->nullable(),
                ]),

            Section::make(__('filament.sections.links_description'))
                ->description(__('filament.sections.links_description_desc'))
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('url')
                            ->label(__('filament.fields.external_url'))
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpan(8)
                            ->disabled(fn($get) => (bool) $get('is_group')),

                        Select::make('url_target')
                            ->label(__('filament.fields.url_target_link'))
                            ->options([
                                '_blank' => __('filament.options.tab_new_alt'),
                                '_self'  => __('filament.options.tab_same_alt'),
                            ])
                            ->default('_blank')
                            ->columnSpan(4)
                            ->disabled(fn($get) => (bool) $get('is_group')),
                    ]),

                    RichEditor::make('content')
                        ->label(__('filament.sections.detail_content'))
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->collapsible(),
        ]);
    }
}
