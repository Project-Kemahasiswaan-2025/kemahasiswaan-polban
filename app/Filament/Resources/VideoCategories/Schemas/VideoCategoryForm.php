<?php

namespace App\Filament\Resources\VideoCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VideoCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.video_category'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('filament.fields.name'))
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('sort_order')
                        ->label(__('filament.fields.sort_order'))
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label(__('filament.fields.is_active'))
                        ->default(true),

                    TextInput::make('type')
                        ->default('video')
                        ->hidden()
                        ->dehydrated(),
                ])
                ->columns(2),
        ]);
    }
}
