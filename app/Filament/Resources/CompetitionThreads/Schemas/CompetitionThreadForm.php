<?php

namespace App\Filament\Resources\CompetitionThreads\Schemas;

use App\Models\Competition;
use App\Models\CompetitionThread;
use App\Models\Poster;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CompetitionThreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make(__('filament.tabs.thread_competition'))
                ->tabs([
                    Tabs\Tab::make(__('filament.tabs.general_info'))
                        ->icon('heroicon-o-identification')
                        ->schema(static::identityTab()),

                    Tabs\Tab::make(__('filament.tabs.visual'))
                        ->icon('heroicon-o-photo')
                        ->schema(static::visualTab()),

                    Tabs\Tab::make(__('filament.tabs.link_timeline'))
                        ->icon('heroicon-o-calendar-days')
                        ->schema(static::linkTimelineTab()),
                ])
                ->persistTabInQueryString()
                ->columnSpanFull(),
        ]);
    }

    // ─── Tab 1: Informasi Umum ──────────────────────────────────────

    protected static function identityTab(): array
    {
        return [
            Grid::make(12)->schema([
                Select::make('competition_id')
                    ->label(__('filament.fields.catalog_item'))
                    ->relationship(
                        name: 'competition',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query
                            ->where('is_group', false)
                            ->orderBy('name')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hintAction(
                        Action::make('create_item')
                            ->icon('heroicon-m-plus')
                            ->url(\App\Filament\Resources\Competitions\CompetitionResource::getUrl('create'), true)
                    )
                    ->columnSpan(8),

                Select::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        'draft'               => __('filament.options.draft'),
                        'ongoing'             => __('filament.options.ongoing'),
                        'registration_closed' => __('filament.options.registration_closed'),
                        'completed'           => __('filament.options.completed'),
                    ])
                    ->default('draft')
                    ->required()
                    ->columnSpan(4),
            ]),

            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label(__('filament.fields.announcement_title'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set, $get) => $set('slug', Str::slug((string) $state)))
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(CompetitionThread::class, 'slug', ignoreRecord: true),
            ]),

            RichEditor::make('content')
                ->label(__('filament.sections.detail_content'))
                ->columnSpanFull(),

            Section::make(__('filament.sections.competition_winners'))
                ->description(__('filament.sections.competition_winners_desc'))
                ->schema([
                    RichEditor::make('announcement_content')
                        ->label(__('filament.fields.announcement_content'))
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }

    // ─── Tab 2: Visual / Poster ───────────────────────────────────

    protected static function visualTab(): array
    {
        return [
            Select::make('poster_id')
                ->label(__('filament.fields.poster_from_gallery'))
                ->relationship(
                    name: 'poster',
                    titleAttribute: 'title',
                    modifyQueryUsing: fn($query) => $query->orderBy('created_at', 'desc')
                )
                ->searchable()
                ->preload()
                ->nullable()
                ->live()
                ->helperText(__('filament.fields.poster_from_gallery_helper')),

            Placeholder::make('poster_preview')
                ->label(__('filament.fields.poster_preview'))
                ->content(function ($get) {
                    $posterId = $get('poster_id');
                    if (! $posterId) return null;

                    $poster = Poster::find($posterId);
                    if (! $poster || ! $poster->image_path) return 'Poster tidak ditemukan.';

                    $url = asset('storage/' . $poster->image_path);
                    return new HtmlString(
                        '<div style="display:inline-block;max-width:240px;">'
                            . '<img src="' . e($url) . '" alt="Preview" style="width:100%;height:auto;border-radius:8px;border:1px solid #e5e7eb;object-fit:contain;">'
                            . '</div>'
                    );
                })
                ->visible(fn($get) => filled($get('poster_id'))),

            FileUpload::make('custom_image')
                ->label(__('filament.fields.custom_image'))
                ->helperText(__('filament.fields.custom_image_helper'))
                ->disk('public')
                ->directory('competition-threads/images')
                ->image()
                ->nullable(),
        ];
    }

    // ─── Tab 3: Link & Timeline ───────────────────────────────────

    protected static function linkTimelineTab(): array
    {
        return [
            Section::make(__('filament.sections.links_simple'))
                ->description(__('filament.sections.links_simple_desc'))
                ->schema([
                    TextInput::make('post_url')
                        ->label(__('filament.fields.post_url'))
                        ->placeholder('https://...')
                        ->url()
                        ->maxLength(500)
                        ->nullable()
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('registration_url')
                            ->label(__('filament.fields.registration_url'))
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),

                        TextInput::make('guidebook_url')
                            ->label(__('filament.fields.guidebook_url'))
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('contact_info')
                            ->label(__('filament.fields.contact_info'))
                            ->placeholder('08xxxxxxxxxx / email@example.com')
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('location')
                            ->label(__('filament.fields.location'))
                            ->placeholder('Gedung A, Kampus Utama')
                            ->maxLength(255)
                            ->nullable(),
                    ]),
                ]),

            Section::make(__('filament.sections.schedule'))
                ->description(__('filament.sections.schedule_desc'))
                ->schema([
                    Grid::make(2)->schema([
                        DatePicker::make('registration_start')
                            ->label(__('filament.fields.registration_start'))
                            ->native(false),

                        DatePicker::make('registration_end')
                            ->label(__('filament.fields.registration_end'))
                            ->native(false),
                    ]),

                    Repeater::make('timelines')
                        ->label(__('filament.fields.timeline_repeater'))
                        ->relationship()
                        ->schema([
                            TextInput::make('label')
                                ->label(__('filament.fields.timeline_label'))
                                ->required(),
                            DatePicker::make('date')
                                ->label(__('filament.fields.date'))
                                ->native(false)
                                ->required(),
                            TextInput::make('sort_order')
                                ->label(__('filament.fields.sort_order'))
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->reorderable()
                        ->orderColumn('sort_order')
                        ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->addActionLabel(__('filament.actions.add_timeline')),
                ]),
        ];
    }
}
