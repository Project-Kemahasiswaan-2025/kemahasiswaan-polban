<?php

namespace App\Filament\Resources\Competitions\Pages;

use App\Filament\Resources\Competitions\CompetitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompetition extends CreateRecord
{
    protected static string $resource = CompetitionResource::class;

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
