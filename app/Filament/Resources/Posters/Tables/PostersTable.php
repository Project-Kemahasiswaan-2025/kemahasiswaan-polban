<?php

namespace App\Filament\Resources\Posters\Tables;

use App\Models\Category;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label(__('filament.sections.poster'))
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label(__('filament.fields.category'))
                    ->badge()
                    ->sortable()
                    ->placeholder('-'),

                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('filament.fields.category'))
                    ->options(
                        Category::query()
                            ->where('type', 'poster')
                            ->orderBy('sort_order')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])->actions([
                EditAction::make()
            ])->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
