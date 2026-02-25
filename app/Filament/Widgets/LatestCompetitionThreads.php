<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CompetitionThreads\CompetitionThreadResource;
use App\Models\CompetitionThread;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestCompetitionThreads extends BaseWidget
{
    protected static ?string $heading = 'Thread Kompetisi Aktif';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CompetitionThreadResource::getEloquentQuery()
                    ->where('status', '!=', 'completed')
                    ->orderBy('registration_end', 'asc')
                    ->orderBy('created_at', 'desc')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'ongoing' => 'success',
                        'registration_closed' => 'warning',
                        'completed' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'ongoing' => 'Berlangsung',
                        'registration_closed' => 'Tutup (Registrasi)',
                        'completed' => 'Selesai',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('registration_end')
                    ->label('Batas Daftar')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(fn(CompetitionThread $record): string => CompetitionThreadResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
