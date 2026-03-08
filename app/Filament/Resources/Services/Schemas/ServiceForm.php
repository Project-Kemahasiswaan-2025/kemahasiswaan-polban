<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Forms\Components\BootstrapIconPicker;
use App\Models\Service;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Row 1
            Section::make(__('filament.sections.service_info'))
                ->description(__('filament.sections.service_info_desc'))
                ->schema([
                    Grid::make(12)->schema([
                        // KIRI
                        Grid::make(12)
                            ->columnSpan(6)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament.fields.service_name'))
                                    ->required()
                                    ->maxLength(180)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if (filled($get('slug'))) {
                                            return;
                                        }
                                        $set('slug', Str::slug((string) $state));
                                    })
                                    ->columnSpanFull(),

                                Grid::make(12)->schema([
                                    TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->unique(Service::class, 'slug', ignoreRecord: true)
                                        ->columnSpan(9),

                                    TextInput::make('sort_order')
                                        ->label(__('filament.fields.sort_order'))
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0)
                                        ->columnSpan(3),
                                ])->columnSpanFull(),

                                BootstrapIconPicker::make('icon')
                                    ->label('Icon')
                                    ->icons(config('bootstrap-icons'))
                                    ->required()
                                    ->columnSpanFull(),

                            ]),

                        // KANAN
                        Grid::make(12)
                            ->columnSpan(6)
                            ->schema([
                                TextInput::make('excerpt')
                                    ->label(__('filament.fields.summary'))
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Section::make(__('filament.sections.action_button'))
                                    ->description(__('filament.sections.action_button_desc'))
                                    ->schema([
                                        Grid::make(12)->schema([
                                            TextInput::make('cta_label')
                                                ->label(__('filament.fields.cta_label'))
                                                ->columnSpan(4),

                                            TextInput::make('cta_url')
                                                ->label(__('filament.fields.cta_url'))
                                                ->url()
                                                ->columnSpan(8),
                                        ]),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),
                    ]),
                ])
                ->columnSpanFull(),

            // Row 2
            Section::make(__('filament.sections.page_content'))
                ->schema([
                    RichEditor::make('content')
                        ->hiddenLabel()
                        ->columnSpanFull(),
                ])->columnSpanFull(),

            // Row 3
            Section::make(__('filament.sections.advanced_links'))
                ->description(__('filament.sections.advanced_links_desc'))
                ->schema([
                    Repeater::make('links')
                        ->label(__('filament.fields.links'))
                        ->relationship('links')
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament.fields.link_title'))
                                ->required(),
                            TextInput::make('url')
                                ->label(__('filament.fields.link_url'))
                                ->url()
                                ->required(),
                            TextInput::make('description')
                                ->label(__('filament.fields.link_description'))
                                ->placeholder(__('filament.fields.link_description_placeholder')),
                        ])
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                ])
                ->collapsible(),

            Section::make(__('filament.sections.documents_downloads'))
                ->description(__('filament.sections.documents_downloads_form_desc'))
                ->schema([
                    Repeater::make('downloads')
                        ->relationship('downloads')
                        ->schema([
                            Grid::make(12)->schema([
                                TextInput::make('name')
                                    ->label(__('filament.fields.document_name'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->columnSpan(8),

                                TextInput::make('sort_order')
                                    ->label(__('filament.fields.sort_order'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(4),
                            ]),

                            FileUpload::make('file_path')
                                ->label('File')
                                ->required()
                                ->disk('public')
                                ->directory('downloads/services')
                                ->live()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    if (!$get('name') && $state) {
                                        $file = is_array($state) ? array_first($state) : $state;
                                        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                            $set('name', (string) str($filename)->replace(['-', '_'], ' ')->title());
                                        }
                                    }
                                }),
                        ])
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                ])
                ->collapsible(),
        ]);
    }
}
