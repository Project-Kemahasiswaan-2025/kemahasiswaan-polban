<?php

namespace App\Filament\Resources\CompetitionThreads\Pages;

use App\Filament\Resources\CompetitionThreads\CompetitionThreadResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCompetitionThread extends ViewRecord
{
    protected static string $resource = CompetitionThreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
