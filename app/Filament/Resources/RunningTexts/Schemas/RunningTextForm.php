<?php

namespace App\Filament\Resources\RunningTexts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RunningTextForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Textarea::make('content')
                ->label('Content')
                ->required()
                ->maxLength(500)
                ->rows(3)
                ->columnSpanFull(),

            Grid::make(12)->schema([
                TextInput::make('duration_seconds')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->default(8)
                    ->minValue(1)
                    ->maxValue(60)
                    ->required()
                    ->helperText('Display duration in seconds')
                    ->columnSpan(8),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required()
                    ->columnSpan(4),
            ])->columnSpanFull(),
        ]);
    }
}
