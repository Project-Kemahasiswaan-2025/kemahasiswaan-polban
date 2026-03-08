<?php

namespace App\Filament\Resources\VideoCategories\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class VideoCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.fields.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
