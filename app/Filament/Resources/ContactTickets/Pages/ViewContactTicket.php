<?php

namespace App\Filament\Resources\ContactTickets\Pages;

use App\Filament\Resources\ContactTickets\ContactTicketResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContactTicket extends ViewRecord
{
    protected static string $resource = ContactTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('whatsapp')
                ->label('WA')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('success')
                ->url(fn(): ?string => $this->record->whatsapp_link)
                ->openUrlInNewTab()
                ->visible(fn(): bool => !empty($this->record->phone)),

            Action::make('email_followup')
                ->label('Email')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->url(fn(): string => $this->record->mailto_link)
                ->openUrlInNewTab(),

            EditAction::make(),
        ];
    }
}
