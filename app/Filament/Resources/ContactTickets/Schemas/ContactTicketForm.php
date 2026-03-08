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
            Section::make(__('filament.sections.ticket_info'))
                ->description(__('filament.sections.ticket_info_desc'))
                ->columns(2)
                ->schema([
                    TextInput::make('ticket_code')
                        ->label(__('filament.fields.ticket_code'))
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('name')
                        ->label(__('filament.fields.sender_name'))
                        ->disabled(),
                    TextInput::make('email')
                        ->label(__('filament.fields.email'))
                        ->disabled(),
                    TextInput::make('phone')
                        ->label(__('filament.fields.phone_wa'))
                        ->disabled(),
                    TextInput::make('subject')
                        ->label(__('filament.fields.subject'))
                        ->disabled()
                        ->columnSpanFull(),
                    Textarea::make('message')
                        ->label(__('filament.fields.message_body'))
                        ->disabled()
                        ->columnSpanFull(),
                ]),

            Section::make(__('filament.sections.admin_followup'))
                ->description(__('filament.sections.admin_followup_desc'))
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
                        ->label(__('filament.fields.admin_note'))
                        ->placeholder(__('filament.fields.admin_note_placeholder'))
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
