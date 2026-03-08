<?php

namespace App\Filament\Resources\RunningTexts\Pages;

use App\Filament\Resources\RunningTexts\RunningTextResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRunningTexts extends ListRecords
{
    protected static string $resource = RunningTextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('md'),
        ];
    }
}
