<?php

namespace App\Filament\Resources\Videos\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Thumb')
                    ->square()
                    ->disk(null),

                TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

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
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('category.name')
                    ->label(__('filament.fields.category'))
                    ->sortable()
                    ->badge(),

                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('filament.fields.category'))
                    ->relationship('category', 'name', fn($query) => $query->where('type', 'video')),
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
