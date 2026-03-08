<?php

namespace App\Filament\Resources\StudentOrganizations\Tables;

use App\Models\StudentOrganization;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StudentOrganizationsTable
{
    public static function configure(Table $table): Table
    {
        $hasParentIdFilter = static function (): bool {
            $request = request();
            $parentId = $request->query('parent_id');

            if (!filled($parentId) && $request->isMethod('post')) {
                $referer = $request->header('referer');
                if ($referer) {
                    $urlQuery = parse_url($referer, PHP_URL_QUERY);
                    if ($urlQuery) {
                        parse_str($urlQuery, $queryParams);
                        $parentId = $queryParams['parent_id'] ?? null;
                    }
                }
            }

            return filled($parentId);
        };

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
                TernaryFilter::make('is_group')->label('Hanya Group'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Action::make('manageMembers')
                    ->label('Kelola Sub Organisasi')
                    ->icon('heroicon-o-view-columns')
                    ->color('info')
                    ->url(fn(StudentOrganization $record): string => \App\Filament\Resources\StudentOrganizations\StudentOrganizationResource::getUrl('index', [
                        'parent_id' => $record->id,
                    ]))
                    ->visible(fn(StudentOrganization $record): bool => (bool) $record->is_group),

                // ViewAction::make(),
                EditAction::make()
                    ->url(fn(StudentOrganization $record): string => \App\Filament\Resources\StudentOrganizations\StudentOrganizationResource::getUrl('edit', [
                        'record' => $record,
                        'parent_id' => $record->parent_id,
                    ])),
                DeleteAction::make(),
            ]);
    }
}
