<?php

namespace App\Filament\Resources\ProfilePages\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProfilePagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                // TextColumn::make('language.name')
                //     ->label('Bahasa')
                //     ->badge()
                //     ->placeholder('Default')
                //     ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                TrashedFilter::make(),
            ])->actions([
                EditAction::make(),
            ])->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
