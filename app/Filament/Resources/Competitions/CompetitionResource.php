<?php

namespace App\Filament\Resources\Competitions;

use App\Filament\Resources\Competitions\Pages\CreateCompetition;
use App\Filament\Resources\Competitions\Pages\EditCompetition;
use App\Filament\Resources\Competitions\Pages\ListCompetitions;
use App\Filament\Resources\Competitions\Pages\ViewCompetition;
use App\Models\Competition;
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
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Kompetisi';

    public static function getLabel(): string
    {
        return 'Kompetisi';
    }

    public static function getPluralLabel(): string
    {
        return 'Kompetisi';
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
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
                                if (filled($get('slug'))) return;
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
                        ->unique(
                            table: Competition::class,
                            column: 'slug',
                            ignoreRecord: true,
                        ),

                    Grid::make(12)->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(4),

                        Toggle::make('is_group')
                            ->label('Kategori')
                            ->helperText('Aktifkan jika ini kategori (Puspresnas, BAKORMA, Mapres).')
                            ->default(false)
                            ->live()
                            ->columnSpan(8),
                    ]),

                    Select::make('parent_id')
                        ->label('Parent Kategori')
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
                        ->disabled(fn($get) => (bool) $get('is_group'))
                        ->helperText('Kosongkan jika ini kategori utama.'),

                    FileUpload::make('cover_image')
                        ->label('Cover (Opsional)')
                        ->disk('public')
                        ->directory('competitions/covers')
                        ->image()
                        ->imageEditor()
                        ->nullable(),
                ]),

            Section::make('Link & Konten')
                ->schema([
                    Grid::make(12)->schema([
                        TextInput::make('url')
                            ->label('URL')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpan(8)
                            ->disabled(fn($get) => (bool) $get('is_group')),

                        Select::make('url_target')
                            ->label('Target')
                            ->options([
                                '_blank' => 'Tab baru',
                                '_self'  => 'Tab yang sama',
                            ])
                            ->default('_blank')
                            ->columnSpan(4)
                            ->disabled(fn($get) => (bool) $get('is_group')),
                    ]),

                    RichEditor::make('content')
                        ->label('Konten (Opsional)')
                        ->nullable()
                        ->columnSpanFull(),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

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

                TernaryFilter::make('is_group')->label('Kategori'),
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
            'index'  => ListCompetitions::route('/'),
            'create' => CreateCompetition::route('/create'),
            'view'   => ViewCompetition::route('/{record}'),
            'edit'   => EditCompetition::route('/{record}/edit'),
        ];
    }
}
