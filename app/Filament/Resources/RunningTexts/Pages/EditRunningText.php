<?php

namespace App\Filament\Resources\RunningTexts\Pages;

use App\Filament\Resources\RunningTexts\RunningTextResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRunningText extends EditRecord
{
    protected static string $resource = RunningTextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
