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
                    ->label('Gambar')
                    ->disk('public'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),


                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                ToggleColumn::make('is_pinned')
                    ->label('Pin')
                    ->sortable(),

                TextColumn::make('active_from')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('active_to')
                    ->label('Sampai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_pinned')->label('Pin'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
