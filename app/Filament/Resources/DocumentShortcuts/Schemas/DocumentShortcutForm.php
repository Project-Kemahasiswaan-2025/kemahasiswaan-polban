<?php

namespace App\Filament\Resources\DocumentShortcuts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DocumentShortcutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu')
                    ->options([
                        'profile' => __('filament.options.dropdown_profile'),
                        'services' => __('filament.options.dropdown_services'),
                        'ormawa' => __('filament.options.dropdown_ormawa'),
                        'achievements' => __('filament.options.dropdown_achievements'),
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('title')
                    ->label(__('filament.fields.submenu_name'))
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->label(__('filament.fields.download_category'))
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('type', 'download')->active()->orderBy('sort_order')
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('download_id', null))
                    ->nullable()
                    ->helperText(__('filament.fields.category_id_helper')),
                Select::make('download_id')
                    ->label(__('filament.fields.download_optional'))
                    ->relationship(
                        name: 'download',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query
                            ->where('is_active', true)
                            ->when($get('category_id'), fn($query, $categoryId) => $query->where('category_id', $categoryId))
                            ->orderBy('sort_order')
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText(__('filament.fields.download_id_helper')),
                TextInput::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }
}
