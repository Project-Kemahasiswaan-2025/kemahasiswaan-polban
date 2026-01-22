<?php

namespace App\Filament\Resources\PosterCategories\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PosterCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Kategori Poster')
                ->schema([
                    Hidden::make('type')
                        ->default('poster')
                        ->dehydrated()
                        ->required(),

                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) return;
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(8),

                        TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(150)
                        ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('type', 'poster')),
                ]),
        ]);
    }
}
