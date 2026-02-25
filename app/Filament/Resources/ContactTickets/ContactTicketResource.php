<?php

namespace App\Filament\Resources\ContactTickets;

use App\Filament\Resources\ContactTickets\Pages;
use App\Models\ContactTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactTicketResource extends Resource
{
    protected static ?string $model = ContactTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 50;

    public static function getLabel(): string
    {
        return 'Tiket';
    }

    public static function getPluralLabel(): string
    {
        return 'Tiket';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Layanan & Bantuan';
    }

    public static function getNavigationLabel(): string
    {
        return 'Tiket Bantuan';
    }

    public static function form(Schema $schema): Schema
    {
        return Schemas\ContactTicketForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Schemas\ContactTicketInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\ContactTicketsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactTickets::route('/'),
            'view' => Pages\ViewContactTicket::route('/{record}'),
            'edit' => Pages\EditContactTicket::route('/{record}/edit'),
        ];
    }
}
