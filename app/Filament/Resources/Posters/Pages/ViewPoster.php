<?php

namespace App\Filament\Resources\Posters\Pages;

use App\Filament\Resources\Posters\PosterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPoster extends ViewRecord
{
    protected static string $resource = PosterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
