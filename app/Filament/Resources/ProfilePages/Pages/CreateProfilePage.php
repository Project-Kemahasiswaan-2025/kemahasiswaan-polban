<?php

namespace App\Filament\Resources\ProfilePages\Pages;

use App\Filament\Resources\ProfilePages\ProfilePageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProfilePage extends CreateRecord
{
    protected static string $resource = ProfilePageResource::class;
}
