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
    protected static ?string $heading = 'Tiket Bantuan Baru';

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
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengirim'),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Tindak Lanjut')
                    ->icon('heroicon-m-eye')
                    ->url(fn(ContactTicket $record): string => ContactTicketResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
