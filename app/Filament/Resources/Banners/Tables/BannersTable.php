<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label(__('filament.fields.image'))
                    ->disk('public'),

                TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable(),


                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->sortable(),

                ToggleColumn::make('is_pinned')
                    ->label(__('filament.fields.is_pinned'))
                    ->sortable(),

                TextColumn::make('active_from')
                    ->label(__('filament.fields.active_from_short'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('active_to')
                    ->label(__('filament.fields.active_to_short'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label(__('filament.fields.is_active')),
                TernaryFilter::make('is_pinned')->label(__('filament.fields.is_pinned')),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
