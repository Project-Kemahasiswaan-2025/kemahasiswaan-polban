<?php

namespace App\Filament\Resources\DocumentShortcuts\Pages;

use App\Filament\Resources\DocumentShortcuts\DocumentShortcutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDocumentShortcuts extends ManageRecords
{
    protected static string $resource = DocumentShortcutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
