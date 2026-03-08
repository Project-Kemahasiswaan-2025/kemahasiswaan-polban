<?php

namespace App\Filament\Resources\Posters\Schemas;

use App\Models\Category;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PosterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.poster'))
                ->schema([
                    Grid::make(12)->schema([
                        FileUpload::make('image_path')
                            ->label(__('filament.fields.poster_file'))
                            ->disk('public')
                            ->directory('posters')
                            ->image()
                            ->required()
                            ->columnSpan(4),

                        Grid::make(12)->schema([
                            Select::make('category_id')
                                ->label(__('filament.fields.category'))
                                ->options(
                                    Category::query()
                                        ->where('type', 'poster')
                                        ->where('is_active', true)
                                        ->orderBy('sort_order')
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->columnSpanFull(),

                            TextInput::make('title')
                                ->label(__('filament.fields.poster_title'))
                                ->required()
                                ->maxLength(180)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    if (filled($get('slug'))) return;
                                    $set('slug', Str::slug((string) $state));
                                })
                                ->columnSpanFull(),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(200)
                                ->unique(ignoreRecord: true)
                                ->columnSpanFull(),

                            // Toggle is removed from form as requested, will use toggle column in table
                        ])->columnSpan(8),
                    ]),
                ]),
        ]);
    }
}
