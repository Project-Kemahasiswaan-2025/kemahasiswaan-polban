<?php

namespace App\Filament\Resources\StudentOrganizations\Pages;

use App\Filament\Resources\StudentOrganizations\StudentOrganizationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentOrganization extends EditRecord
{
    protected static string $resource = StudentOrganizationResource::class;

    protected function getRedirectUrl(): string
    {
        $parentId = $this->record->parent_id;

        return $this->getResource()::getUrl('index', [
            'tableFilters' => [
                'parent_id' => [
                    'value' => $parentId,
                ],
            ],
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
