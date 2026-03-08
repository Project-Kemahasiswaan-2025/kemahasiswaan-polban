<?php

namespace App\Filament\Resources\ContactTickets\Schemas;

use App\Models\ContactTicket;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactTicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextEntry::make('ticket_code')
                ->label(__('filament.fields.ticket_code')),
            TextEntry::make('name')
                ->label(__('filament.fields.sender_name')),
            TextEntry::make('email')
                ->label(__('filament.fields.email')),
            TextEntry::make('phone')
                ->label(__('filament.fields.phone_wa'))
                ->placeholder('-'),
            TextEntry::make('subject')
                ->label(__('filament.fields.subject')),
            TextEntry::make('message')
                ->label(__('filament.fields.message_body'))
                ->columnSpanFull(),
            TextEntry::make('status')
                ->label(__('filament.fields.status'))
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    ContactTicket::STATUS_ISSUED => 'danger',
                    ContactTicket::STATUS_FOLLOW_UP => 'warning',
                    ContactTicket::STATUS_COMPLETED => 'success',
                    default => 'gray',
                })
                ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', (string) $state))),
            TextEntry::make('admin_note')
                ->label(__('filament.fields.admin_note'))
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('created_at')
                ->label(__('filament.fields.sent_at'))
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->label(__('filament.fields.updated_at_label'))
                ->dateTime()
                ->placeholder('-'),
        ]);
    }
}
