<?php

namespace App\Filament\Resources\StudentOrganizations\Pages;

use App\Filament\Resources\StudentOrganizations\StudentOrganizationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentOrganization extends ViewRecord
{
    protected static string $resource = StudentOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
