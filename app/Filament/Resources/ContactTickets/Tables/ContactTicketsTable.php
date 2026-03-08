<?php

namespace App\Filament\Resources\ContactTickets\Tables;

use App\Models\ContactTicket;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_code')
            ->columns([
                TextColumn::make('ticket_code')
                    ->label(__('filament.fields.code'))
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament.fields.name'))
                    ->searchable(),
                TextColumn::make('subject')
                    ->label(__('filament.fields.subject'))
                    ->limit(30)
                    ->searchable(),
                \Filament\Tables\Columns\SelectColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        ContactTicket::STATUS_ISSUED => 'Issued',
                        ContactTicket::STATUS_FOLLOW_UP => 'Follow Up',
                        ContactTicket::STATUS_COMPLETED => 'Completed',
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.fields.sent_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
