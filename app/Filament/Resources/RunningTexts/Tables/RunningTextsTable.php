<?php

namespace App\Filament\Resources\RunningTexts\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RunningTextsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content')
                    ->label('Content')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('duration_seconds')
                    ->label('Duration (s)')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                EditAction::make()
                    ->modalWidth('md'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
