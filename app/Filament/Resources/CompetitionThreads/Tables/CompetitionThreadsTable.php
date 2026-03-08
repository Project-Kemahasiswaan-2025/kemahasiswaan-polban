<?php

namespace App\Filament\Resources\CompetitionThreads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CompetitionThreadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('custom_image')
                    ->label('Visual')
                    ->disk('public')
                    ->square()
                    ->visible(fn($record) => $record?->visual_type === 'manual'),

                ImageColumn::make('poster.image_path')
                    ->label('Visual')
                    ->disk('public')
                    ->square()
                    ->visible(fn($record) => $record?->visual_type === 'poster'),

                TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('competition.name')
                    ->label(__('filament.fields.catalog_item'))
                    ->sortable()
                    ->badge(),

                TextColumn::make('registration_end')
                    ->label(__('filament.fields.deadline'))
                    ->date()
                    ->sortable()
                    ->color(fn($state) => $state && $state->isPast() ? 'danger' : 'success'),

                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active')),

                ToggleColumn::make('is_featured')
                    ->label('Feature'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('competition_id')
                    ->label(__('filament.filters.competition'))
                    ->relationship('competition', 'name'),

                TernaryFilter::make('is_active')->label(__('filament.fields.is_active')),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
