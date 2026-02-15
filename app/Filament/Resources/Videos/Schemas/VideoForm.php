<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Video YouTube')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(180)
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->nullable()
                        ->columnSpanFull(),

                    Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name', fn($query) => $query->where('type', 'video'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->url()
                            ->nullable()
                            ->live(debounce: 600)
                            ->afterStateUpdated(function ($state, $set) {
                                $id = \App\Models\Video::extractYoutubeId((string) $state);
                                $set('youtube_id', $id);

                                if ($id) {
                                    $set('thumbnail_url', "https://i.ytimg.com/vi/{$id}/maxresdefault.jpg");
                                } else {
                                    $set('thumbnail_url', null);
                                }
                            })
                            ->columnSpan(12),

                        TextInput::make('youtube_id')
                            ->label('YouTube ID')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(6),

                        TextInput::make('thumbnail_url')
                            ->label('Thumbnail URL (Auto)')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(6),
                    ]),

                    Grid::make(12)->schema([
                        \Filament\Schemas\Components\Text::make('')
                            ->view('components.hr-divider', [
                                'label' => 'Pengaturan Tampilan',
                                'icon' => 'fas fa-cogs'
                            ])
                            ->columnSpanFull(),
                    ]),

                    Grid::make(6)->schema([
                        DateTimePicker::make('active_from')
                            ->label('Aktif Mulai')
                            ->seconds(false)
                            ->nullable()
                            ->live()
                            ->suffixAction(
                                Action::make('setNow')
                                    ->icon('heroicon-m-clock')
                                    ->tooltip('Set hari ini')
                                    ->action(fn($set) => $set('active_from', now()->seconds(0)))
                            )
                            ->afterStateUpdated(function ($set, $get, $state) {
                                if (blank($state)) {
                                    $set('active_to', null);
                                    return;
                                }
                            })
                            ->columnSpan(3),

                        DateTimePicker::make('active_to')
                            ->label('Aktif Sampai')
                            ->seconds(false)
                            ->nullable()
                            ->columnSpan(3),
                    ]),

                    Grid::make(4)->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(1),

                        Toggle::make('is_pinned')
                            ->label('Pin')
                            ->default(false)
                            ->columnSpan(1),
                    ]),
                ])
                ->columns(1),
        ]);
    }
}
