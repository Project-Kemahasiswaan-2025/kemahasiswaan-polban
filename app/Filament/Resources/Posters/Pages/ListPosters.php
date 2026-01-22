<?php

namespace App\Filament\Resources\Posters\Pages;

use App\Filament\Resources\Posters\PosterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosters extends ListRecords
{
    protected static string $resource = PosterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
