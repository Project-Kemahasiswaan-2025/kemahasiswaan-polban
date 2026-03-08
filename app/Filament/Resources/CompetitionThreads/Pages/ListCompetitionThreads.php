<?php

namespace App\Filament\Resources\CompetitionThreads\Pages;

use App\Filament\Resources\CompetitionThreads\CompetitionThreadResource;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionThreads extends ListRecords
{
    protected static string $resource = CompetitionThreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
