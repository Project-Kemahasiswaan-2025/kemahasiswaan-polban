<?php

namespace App\Filament\Resources\VideoCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VideoCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('type')
                ->default('video')
                ->dehydrated()
                ->required(),

            TextInput::make('name')
                ->label(__('filament.fields.name'))
                ->required()
                ->maxLength(120)
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set, $get) {
                    if (filled($get('slug'))) return;
                    $set('slug', Str::slug((string) $state));
                })
                ->columnSpanFull(),

            Grid::make(12)->schema([
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(150)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn($rule) =>
                        $rule->where('type', 'video')
                    )
                    ->columnSpan(9),

                TextInput::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(3),
            ])->columnSpanFull(),
        ]);
    }
}
