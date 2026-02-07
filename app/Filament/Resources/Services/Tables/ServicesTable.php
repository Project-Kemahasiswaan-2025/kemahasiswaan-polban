<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('children_count')
                    ->label('Sub-Layanan')
                    ->counts('children')
                    ->badge(),

                TextColumn::make('downloads_count')
                    ->label('Dokumen')
                    ->counts('downloads')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ]);
    }
}
