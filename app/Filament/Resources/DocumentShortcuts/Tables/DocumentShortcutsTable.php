<?php

namespace App\Filament\Resources\DocumentShortcuts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class DocumentShortcutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('menu')
                    ->label(__('filament.fields.main_menu'))
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'profile' => __('filament.options.profile'),
                        'services' => __('filament.options.services'),
                        'ormawa' => __('filament.options.ormawa'),
                        'achievements' => __('filament.options.achievements'),
                        default => $state,
                    }),
                TextColumn::make('title')
                    ->label(__('filament.fields.submenu_label'))
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label(__('filament.fields.category'))
                    ->searchable(),
                TextColumn::make('download.name')
                    ->label(__('filament.fields.document'))
                    ->placeholder(__('filament.placeholders.all_categories'))
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label(__('filament.fields.is_active')),
                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('menu')
                    ->label(__('filament.fields.main_menu'))
                    ->options([
                        'profile' => __('filament.options.profile'),
                        'services' => __('filament.options.services'),
                        'ormawa' => __('filament.options.ormawa'),
                        'achievements' => __('filament.options.achievements'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
