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
                    ->label(__('filament.fields.document_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('filament.fields.category_section'))
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                TextColumn::make('file_size')
                    ->label(__('filament.fields.file_size'))
                    ->formatStateUsing(fn($state) => $state ? number_format($state / 1024, 1) . ' KB' : '-')
                    ->sortable(),

                TextColumn::make('file_type')
                    ->label(__('filament.fields.file_type'))
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at_label'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label(__('filament.fields.is_active')),
            ]);
    }
}
