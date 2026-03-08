<?php

namespace App\Filament\Resources\PosterCategories\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PosterCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                TrashedFilter::make(),
            ])->actions([
                EditAction::make()
                    ->modalWidth('md')
            ])->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
