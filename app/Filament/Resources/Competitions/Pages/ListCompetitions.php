<?php

namespace App\Filament\Resources\Competitions\Pages;

use App\Filament\Resources\Competitions\CompetitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitions extends ListRecords
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn(): string => CompetitionResource::getUrl('create', [
                    'parent_id' => data_get(request()->query('tableFilters', []), 'parent_id.value'),
                ])),
        ];
    }
}
