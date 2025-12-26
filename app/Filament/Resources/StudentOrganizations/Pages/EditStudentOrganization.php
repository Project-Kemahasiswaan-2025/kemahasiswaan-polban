<?php

namespace App\Filament\Resources\StudentOrganizations\Pages;

use App\Filament\Resources\StudentOrganizations\StudentOrganizationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentOrganization extends EditRecord
{
    protected static string $resource = StudentOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
