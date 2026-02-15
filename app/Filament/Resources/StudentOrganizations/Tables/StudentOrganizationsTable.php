<?php

namespace App\Filament\Resources\StudentOrganizations\Tables;

use App\Models\StudentOrganization;
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

class StudentOrganizationsTable
{
    public static function configure(Table $table): Table
    {
        $hasParentIdFilter = static fn(): bool =>
        filled(data_get(request()->query('tableFilters', []), 'parent_id.value'));

        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('is_group')
                    ->visible(fn() => ! $hasParentIdFilter())
                    ->label('Group')
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
                    ->label('Kategori')
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

                TernaryFilter::make('is_group')->label('Hanya Group'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Action::make('manageMembers')
                    ->label('Kelola Sub Organisasi')
                    ->icon('heroicon-o-view-columns')
                    ->color('info')
                    ->url(fn(StudentOrganization $record): string => \App\Filament\Resources\StudentOrganizations\StudentOrganizationResource::getUrl('index', [
                        'tableFilters' => [
                            'parent_id' => [
                                'value' => $record->id,
                            ],
                        ],
                    ]))
                    ->visible(fn(StudentOrganization $record): bool => (bool) $record->is_group),

                // ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
