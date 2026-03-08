<?php

namespace App\Filament\Resources\Competitions\Tables;

use App\Models\Competition;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CompetitionsTable
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
                TernaryFilter::make('is_group')->label('Hanya Kategori'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Action::make('manageSub')
                    ->label('Kelola Sub')
                    ->icon('heroicon-o-view-columns')
                    ->color('info')
                    ->url(fn(Competition $record): string => \App\Filament\Resources\Competitions\CompetitionResource::getUrl('index', [
                        'parent_id' => $record->id,
                    ]))
                    ->visible(fn(Competition $record): bool => (bool) $record->is_group),

                EditAction::make()
                    ->url(fn(\App\Models\Competition $record): string => \App\Filament\Resources\Competitions\CompetitionResource::getUrl('edit', [
                        'record' => $record,
                        'parent_id' => $record->parent_id,
                    ])),
                DeleteAction::make(),
            ]);
    }
}
