<?php

namespace App\Filament\Resources\StudentOrganizations\Schemas;

use App\Models\StudentOrganization;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StudentOrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(9),

                        TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(3),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, $get) => $rule
                                ->where('parent_id', $get('parent_id'))
                                ->whereNull('deleted_at')
                        ),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(4),

                        Toggle::make('is_group')
                            ->label('Punya Sub Organisasi')
                            ->helperText('Untuk kategori (HMJ/UKM).')
                            ->default(false)
                            ->live()
                            ->hidden(fn($get) => (bool) $get('child_mode_enabled') || filled($get('parent_id')))
                            ->columnSpan(8),
                    ]),

                    \Filament\Forms\Components\Hidden::make('child_mode_enabled')
                        ->afterStateHydrated(fn($set, $state) => $set('child_mode_enabled', $state ?? filled(request()->query('parent_id'))))
                        ->dehydrated(false),

                    Select::make('parent_id')
                        ->label('Kategori')
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query
                                ->whereNull('parent_id')
                                ->where('is_group', true)
                                ->orderBy('sort_order')
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->default(fn() => request()->query('parent_id'))
                        ->hidden(fn($get) => (bool) $get('is_group'))
                        ->disabled(fn($get) => (bool) $get('child_mode_enabled'))
                        ->dehydrated(),

                    \Filament\Forms\Components\Hidden::make('parent_id')
                        ->default(fn() => request()->query('parent_id'))
                        ->visible(fn($get) => (bool) $get('child_mode_enabled')),

                    Grid::make(12)->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('org/logos')
                            ->image()
                            ->columnSpan(3),

                        FileUpload::make('cover_image')
                            ->label('Cover')
                            ->disk('public')
                            ->directory('org/covers')
                            ->image()
                            ->columnSpan(9),
                    ]),
                ]),

            Section::make('Konten')
                ->schema([
                    RichEditor::make('content')
                        ->label('Konten')
                        ->nullable()
                        ->columnSpanFull(),

                    Section::make('Call To Action')
                        ->schema([
                            Grid::make(12)->schema([
                                TextInput::make('cta_label')
                                    ->label('CTA Label')
                                    ->maxLength(60)
                                    ->columnSpan(4),

                                TextInput::make('cta_url')
                                    ->label('CTA URL')
                                    ->url()
                                    ->columnSpan(8),
                            ]),
                        ])
                        ->collapsed(),
                ]),
        ]);
    }
}
