<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactTickets\ContactTicketResource;
use App\Models\ContactTicket;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NewestContactTickets extends BaseWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('filament.widgets.contact_tickets.heading');
    }

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactTicketResource::getEloquentQuery()
                    ->where('status', 'issued')
                    ->latest()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')
                    ->label(__('filament.fields.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.fields.sender_name')),
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('filament.fields.subject'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.arrived_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('filament.actions.follow_up'))
                    ->icon('heroicon-m-eye')
                    ->url(fn(ContactTicket $record): string => ContactTicketResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
