<?php

namespace App\Filament\Resources\ContactTickets\Pages;

use App\Filament\Resources\ContactTickets\ContactTicketResource;
use Filament\Resources\Pages\ListRecords;

class ListContactTickets extends ListRecords
{
    protected static string $resource = ContactTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action needed as tickets are created via frontend
        ];
    }
}
