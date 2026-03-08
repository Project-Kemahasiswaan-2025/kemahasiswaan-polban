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
                    ->label('Menu Utama')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'profile' => 'Profil',
                        'services' => 'Layanan',
                        'ormawa' => 'Ormawa',
                        'achievements' => 'Prestasi',
                        default => $state,
                    }),
                TextColumn::make('title')
                    ->label('Label Sub Menu')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('download.name')
                    ->label('Dokumen')
                    ->placeholder('Seluruh Kategori')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('menu')
                    ->label('Menu Utama')
                    ->options([
                        'profile' => 'Profil',
                        'services' => 'Layanan',
                        'ormawa' => 'Ormawa',
                        'achievements' => 'Prestasi',
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
