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
            Section::make(__('filament.sections.metadata'))
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('name')
                            ->label(__('filament.fields.name'))
                            ->required()
                            ->maxLength(180)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpan(6),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule, $get) => $rule
                                    ->where('parent_id', $get('parent_id'))
                                    ->whereNull('deleted_at')
                            )
                            ->columnSpan(4),

                        TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('excerpt')
                        ->label(__('filament.fields.short_description'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->default(true)
                            ->columnSpan(2),
                        Toggle::make('is_group')
                            ->label(__('filament.fields.has_sub_org'))
                            ->helperText('Group/Parent Organization.')
                            ->default(false)
                            ->live()
                            ->hidden(fn($get) => (bool) $get('child_mode_enabled') || filled($get('parent_id')))
                            ->columnSpan(4),

                        Select::make('parent_id')
                            ->label(__('filament.fields.category'))
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
                            ->dehydrated()
                            ->columnSpan(6),
                    ]),

                    \Filament\Forms\Components\Hidden::make('child_mode_enabled')
                        ->afterStateHydrated(fn($set, $state) => $set('child_mode_enabled', $state ?? filled(request()->query('parent_id'))))
                        ->dehydrated(false),

                    \Filament\Forms\Components\Hidden::make('parent_id')
                        ->default(fn() => request()->query('parent_id'))
                        ->visible(fn($get) => (bool) $get('child_mode_enabled')),

                    Grid::make(12)->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('org/logos')
                            ->image()
                            ->columnSpan(4),

                        FileUpload::make('cover_image')
                            ->label('Cover')
                            ->disk('public')
                            ->directory('org/covers')
                            ->image()
                            ->columnSpan(8),
                    ]),
                ])
                ->columnSpanFull(),

            Section::make(__('filament.sections.detail_content'))
                ->schema([
                    RichEditor::make('content')
                        ->label(__('filament.fields.full_description'))
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
