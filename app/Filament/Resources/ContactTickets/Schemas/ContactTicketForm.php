<?php

namespace App\Filament\Resources\ContactTickets\Schemas;

use App\Models\ContactTicket;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Tiket')
                ->description('Data yang dikirimkan oleh pengguna.')
                ->columns(2)
                ->schema([
                    TextInput::make('ticket_code')
                        ->label('Kode Tiket')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('name')
                        ->label('Nama Pengirim')
                        ->disabled(),
                    TextInput::make('email')
                        ->label('Email')
                        ->disabled(),
                    TextInput::make('phone')
                        ->label('Nomor Telepon / WA')
                        ->disabled(),
                    TextInput::make('subject')
                        ->label('Subjek')
                        ->disabled()
                        ->columnSpanFull(),
                    Textarea::make('message')
                        ->label('Isi Pesan')
                        ->disabled()
                        ->columnSpanFull(),
                ]),

            Section::make('Tindak Lanjut Admin')
                ->description('Kelola status dan catatan untuk tiket ini.')
                ->schema([
                    Select::make('status')
                        ->options([
                            ContactTicket::STATUS_ISSUED => 'Issued',
                            ContactTicket::STATUS_FOLLOW_UP => 'Follow Up',
                            ContactTicket::STATUS_COMPLETED => 'Completed',
                        ])
                        ->required()
                        ->native(false),
                    Textarea::make('admin_note')
                        ->label('Catatan Internal')
                        ->placeholder('Tuliskan catatan tindak lanjut di sini...')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
