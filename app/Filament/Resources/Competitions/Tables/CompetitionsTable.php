<?php

namespace App\Filament\Resources\Competitions\Tables;

use App\Models\Competition;
use Filament\Actions\Action;
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

class CompetitionsTable
{
    public static function configure(Table $table): Table
    {
        $hasParentIdFilter = static fn(): bool =>
        filled(data_get(request()->query('tableFilters', []), 'parent_id.value'));

        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Sampul')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('is_group')
                    ->visible(fn() => ! $hasParentIdFilter())
                    ->label('Kategori')
                    ->boolean()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Induk Kategori')
                    ->relationship(
                        name: 'parent',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query
                            ->whereNull('parent_id')
                            ->where('is_group', true)
                            ->orderBy('sort_order')
                    )
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_group')->label('Hanya Kategori'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Action::make('manageSub')
                    ->label('Kelola Sub')
                    ->icon('heroicon-o-view-columns')
                    ->color('info')
                    ->url(fn(Competition $record): string => \App\Filament\Resources\Competitions\CompetitionResource::getUrl('index', [
                        'tableFilters' => [
                            'parent_id' => [
                                'value' => $record->id,
                            ],
                        ],
                    ]))
                    ->visible(fn(Competition $record): bool => (bool) $record->is_group),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
