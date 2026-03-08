<?php

namespace App\Filament\Resources\DownloadCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DownloadCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('type')
                ->default('download')
                ->dehydrated()
                ->required(),

            TextInput::make('name')
                ->label(__('filament.fields.section_name'))
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
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('type', 'download'))
                    ->columnSpan(9),

                TextInput::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(3),
            ])->columnSpanFull(),
        ]);
    }
}
