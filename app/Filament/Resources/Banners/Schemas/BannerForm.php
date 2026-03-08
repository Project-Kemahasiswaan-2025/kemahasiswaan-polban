<?php

namespace App\Filament\Resources\Banners\Schemas;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.banner'))
                ->schema([
                    FileUpload::make('image_path')
                        ->label(__('filament.fields.image'))
                        ->disk('public')
                        ->directory('banners')
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull()
                        ->helperText(__('filament.fields.image_helper')),

                    Grid::make(12)->schema([
                        TextInput::make('title')
                            ->label(__('filament.fields.title'))
                            ->maxLength(150)
                            ->required()
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label(__('filament.fields.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('url')
                            ->label(__('filament.fields.url_optional'))
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(255)
                            ->nullable()
                            ->live()
                            ->columnSpanFull(),

                        Select::make('url_target')
                            ->label(__('filament.fields.url_target'))
                            ->options([
                                '_self' => __('filament.options.tab_same'),
                                '_blank' => __('filament.options.tab_new'),
                            ])
                            ->default('_self')
                            ->visible(fn($get): bool => filled($get('url')))
                            ->dehydrated(fn($get): bool => filled($get('url')))
                            ->columnSpanFull(),
                    ]),

                    Grid::make(12)->schema([
                        Text::make('')
                            ->view('components.hr-divider', [
                                'label' => __('filament.sections.display_settings'),
                                'icon' => 'fas fa-cogs'
                            ])
                            ->columnSpanFull(),
                    ]),


                    Grid::make(6)->schema([
                        DateTimePicker::make('active_from')
                            ->label(__('filament.fields.active_from'))
                            ->seconds(false)
                            ->nullable()
                            ->live()
                            ->suffixAction(
                                Action::make('setNow')
                                    ->icon('heroicon-m-clock')
                                    ->tooltip(__('filament.actions.set_today'))
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
                            ->label(__('filament.fields.active_to'))
                            ->seconds(false)
                            ->nullable()
                            ->columnSpan(3),
                    ]),

                    Grid::make(4)->schema([
                        Toggle::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->default(true)
                            ->columnSpan(1),

                        Toggle::make('is_pinned')
                            ->label(__('filament.fields.is_pinned'))
                            ->default(false)
                            ->columnSpan(1),
                    ]),
                ])
                ->columns(1),
        ]);
    }
}
