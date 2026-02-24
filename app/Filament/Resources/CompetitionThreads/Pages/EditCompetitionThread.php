<?php

namespace App\Filament\Resources\CompetitionThreads\Pages;

use App\Filament\Resources\CompetitionThreads\CompetitionThreadResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionThread extends EditRecord
{
    protected static string $resource = CompetitionThreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
