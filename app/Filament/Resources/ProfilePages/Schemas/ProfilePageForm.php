<?php

namespace App\Filament\Resources\ProfilePages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProfilePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make(12)->schema([
                Section::make(__('filament.sections.information'))
                    ->columnSpan(7)
                    ->schema([
                        Hidden::make('section')
                            ->default('profile')
                            ->dehydrated()
                            ->required(),

                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpanFull(),
                        Grid::make(12)->schema([
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(200)
                                ->unique(
                                    ignoreRecord: true,
                                    modifyRuleUsing: fn($rule) => $rule->where('section', 'profile'),
                                )->columnSpan(10),

                            TextInput::make('sort_order')
                                ->label('Order')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->columnSpan(2),
                        ]),
                    ]),

                Section::make(__('filament.sections.document_optional'))
                    ->columnSpan(5)
                    ->schema([
                        FileUpload::make('document_path')
                            ->label(__('filament.fields.document_display'))
                            ->disk('public')
                            ->directory('pages/docs/profile')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable()
                            ->helperText(__('filament.fields.document_helper')),
                    ]),

                Section::make(__('filament.sections.content'))
                    ->columnSpanFull()
                    ->schema([
                        RichEditor::make('content')
                            ->label(__('filament.fields.content'))
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ])->columnSpanFull(),
        ]);
    }
}
