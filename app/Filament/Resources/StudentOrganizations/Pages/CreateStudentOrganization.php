<?php

namespace App\Filament\Resources\StudentOrganizations\Pages;

use App\Filament\Resources\StudentOrganizations\StudentOrganizationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentOrganization extends CreateRecord
{
    protected static string $resource = StudentOrganizationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (filled($parentId = request()->query('parent_id'))) {
            $data['parent_id'] = $parentId;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $parentId = $this->record->parent_id;

        return $this->getResource()::getUrl('index', [
            'parent_id' => $parentId,
        ]);
    }
}
