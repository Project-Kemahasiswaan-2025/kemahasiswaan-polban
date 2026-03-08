<?php

namespace App\Filament\Resources\StudentOrganizations\Pages;

use App\Filament\Resources\StudentOrganizations\StudentOrganizationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentOrganizations extends ListRecords
{
    protected static string $resource = StudentOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn (): string => StudentOrganizationResource::getUrl('create', [
                    'parent_id' => data_get(request()->query('tableFilters', []), 'parent_id.value'),
                ])),
        ];
    }
}
