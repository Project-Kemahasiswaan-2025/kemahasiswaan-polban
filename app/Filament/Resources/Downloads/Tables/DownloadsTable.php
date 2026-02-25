<?php

namespace App\Filament\Resources\Downloads\Tables;

use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DownloadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori (Section)')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn($state) => $state ? number_format($state / 1024, 1) . ' KB' : '-')
                    ->sortable(),

                TextColumn::make('file_type')
                    ->label('Tipe File')
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ]);
    }
}
