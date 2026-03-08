<?php

namespace App\Filament\Resources\Competitions\Tables;

use App\Models\Competition;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
        $hasParentIdFilter = static function (): bool {
            $request = request();
            return filled($request->query('parent_id'))
                || filled(data_get($request->query('tableFilters', []), 'parent_id.value'))
                || filled(data_get($request->query('tableFilters', []), 'parent_id_state.value'))
                || filled(data_get($request->all(), 'tableFilters.parent_id_state.value'))
                || filled(data_get($request->all(), 'tableFilters.parent_id.value'))
                || filled(data_get($request->all(), 'components.0.snapshot.memo.data.tableFilters.parent_id_state.value'))
                || filled(data_get($request->all(), 'components.0.snapshot.memo.data.tableFilters.parent_id.value'));
        };

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
                    ->default(fn() => request()->query('parent_id') ?? data_get(request()->query('tableFilters', []), 'parent_id.value') ?? data_get(request()->all(), 'tableFilters.parent_id.value'))
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

                // Hidden filter to persist state
                SelectFilter::make('parent_id_state')
                    ->hidden()
                    ->default(fn() => request()->query('parent_id') ?? data_get(request()->query('tableFilters', []), 'parent_id.value') ?? data_get(request()->all(), 'tableFilters.parent_id.value'))
                    ->query(fn($query) => $query),

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
                            'parent_id_state' => [
                                'value' => $record->id,
                            ],
                        ],
                    ]))
                    ->visible(fn(Competition $record): bool => (bool) $record->is_group),

                EditAction::make()
                    ->url(fn(Competition $record): string => \App\Filament\Resources\Competitions\CompetitionResource::getUrl('edit', [
                        'record' => $record,
                        'parent_id' => $record->parent_id,
                    ])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
