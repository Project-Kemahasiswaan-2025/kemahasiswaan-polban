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
                ->label('Kode Tiket'),
            TextEntry::make('name')
                ->label('Nama Pengirim'),
            TextEntry::make('email')
                ->label('Email'),
            TextEntry::make('phone')
                ->label('Nomor Telepon / WA')
                ->placeholder('-'),
            TextEntry::make('subject')
                ->label('Subjek'),
            TextEntry::make('message')
                ->label('Isi Pesan')
                ->columnSpanFull(),
            TextEntry::make('status')
                ->label('Status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    ContactTicket::STATUS_ISSUED => 'danger',
                    ContactTicket::STATUS_FOLLOW_UP => 'warning',
                    ContactTicket::STATUS_COMPLETED => 'success',
                    default => 'gray',
                })
                ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', (string) $state))),
            TextEntry::make('admin_note')
                ->label('Catatan Internal')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('created_at')
                ->label('Tgl Kirim')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->label('Tgl Update')
                ->dateTime()
                ->placeholder('-'),
        ]);
    }
}
