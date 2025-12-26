<?php

namespace App\Filament\Resources\StudentOrganizations;

use App\Filament\Resources\StudentOrganizations\Pages\CreateStudentOrganization;
use App\Filament\Resources\StudentOrganizations\Pages\EditStudentOrganization;
use App\Filament\Resources\StudentOrganizations\Pages\ListStudentOrganizations;
use App\Filament\Resources\StudentOrganizations\Pages\ViewStudentOrganization;
use App\Models\StudentOrganization;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StudentOrganizationResource extends Resource
{
    protected static ?string $model = StudentOrganization::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Ormawa';

    public static function getLabel(): string
    {
        return 'Ormawa';
    }

    public static function getPluralLabel(): string
    {
        return 'Ormawa';
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordUrlAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi')
                ->schema([
                    Select::make('language_id')
                        ->label('Bahasa')
                        ->options(\App\Models\Language::active()->pluck('name', 'id'))
                        ->default(fn() => activeLanguage()?->id)
                        ->required()
                        ->native(false)
                        ->columnSpanFull(),

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
                            ->columnSpan(10),

                        TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(2),
                    ]),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(200)
                        ->unique(ignoreRecord: true),

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
                            ->columnSpan(8),
                    ]),

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
                        ->disabled(fn($get) => (bool) $get('is_group')),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('language.icon')
                    ->label('')
                    ->size(TextSize::Large),

                TextColumn::make('language.name')
                    ->label('Bahasa')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_group')
                    ->label('Group')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Kategori')
                    ->placeholder('Kategori Utama')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('language_id')
                    ->label('Bahasa')
                    ->relationship('language', 'name')
                    ->preload(),

                SelectFilter::make('parent_id')
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
                    ->preload(),

                TernaryFilter::make('is_group')->label('Group'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentOrganizations::route('/'),
            'create' => CreateStudentOrganization::route('/create'),
            'view' => ViewStudentOrganization::route('/{record}'),
            'edit' => EditStudentOrganization::route('/{record}/edit'),
        ];
    }
}
