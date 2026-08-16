<?php

namespace App\Filament\Resources\Downloads\Schemas;

use App\Models\Download;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DownloadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.document_info'))
                ->schema([
                    Radio::make('type')
                        ->label(__('filament.fields.document_source_type'))
                        ->options([
                            'file' => __('filament.fields.source_file'),
                            'link' => __('filament.fields.source_link'),
                        ])
                        ->default('file')
                        ->inline()
                        ->live(),

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

                    Select::make('category_id')
                        ->label(__('filament.fields.category_landing_page'))
                        ->relationship('category', 'name', fn($query) => $query->where('type', 'download'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->placeholder(__('filament.placeholders.category_select')),

                    FileUpload::make('file_path')
                        ->label(__('filament.fields.document_file'))
                        ->visible(fn($get) => $get('type') === 'file' || !$get('type'))
                        ->required(fn($get) => $get('type') === 'file' || !$get('type'))
                        ->disk('public')
                        ->directory('downloads/general')
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

                    TextInput::make('external_url')
                        ->label(__('filament.fields.external_url_link'))
                        ->placeholder('https://example.com/dokumen.pdf')
                        ->url()
                        ->visible(fn($get) => $get('type') === 'link')
                        ->required(fn($get) => $get('type') === 'link')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if ($state && filter_var($state, FILTER_VALIDATE_URL)) {
                                $analysis = Download::analyzeUrl($state);
                                if (!$get('name') && !empty($analysis['suggested_name'])) {
                                    $set('name', $analysis['suggested_name']);
                                }
                            }
                        }),
                ]),
        ]);
    }
}
