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
    protected static ?string $heading = null;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('filament.widgets.competition_threads.heading');
    }

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
                    ->label(__('filament.fields.title'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'ongoing' => 'success',
                        'registration_closed' => 'warning',
                        'completed' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => __('filament.options.draft'),
                        'ongoing' => __('filament.options.ongoing'),
                        'registration_closed' => __('filament.options.registration_closed'),
                        'completed' => __('filament.options.completed'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('registration_end')
                    ->label(__('filament.fields.deadline'))
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('filament.actions.view'))
                    ->url(fn(CompetitionThread $record): string => CompetitionThreadResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
