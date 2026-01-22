<?php

namespace App\Filament\Resources\ProfilePages\Pages;

use App\Filament\Resources\ProfilePages\ProfilePageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProfilePage extends ViewRecord
{
    protected static string $resource = ProfilePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
