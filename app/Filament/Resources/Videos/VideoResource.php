<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\VideoResource\Schemas\VideoInfolist;
use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Filament\Resources\Videos\Pages\ViewVideo;
use App\Models\Language;
use App\Models\Video;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Video YouTube')
                ->schema([
                    Select::make('language_id')
                        ->label('Bahasa')
                        ->options(Language::active()->pluck('name', 'id'))
                        ->default(fn () => activeLanguage()?->id)
                        ->required()
                        ->native(false)
                        ->columnSpanFull(),

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

                    Grid::make(12)->schema([
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->url()
                            ->nullable()
                            ->live(debounce: 600)
                            ->afterStateUpdated(function ($state, $set) {
                                $id = self::extractYoutubeId((string) $state);
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
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(3),

                        Toggle::make('is_pinned')
                            ->label('Pin')
                            ->default(false)
                            ->columnSpan(3),

                        DateTimePicker::make('published_at')
                            ->label('Publish At')
                            ->seconds(false)
                            ->nullable()
                            ->columnSpan(6),
                    ]),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Thumb')
                    ->square(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('language.icon')
                    ->label('')
                    ->size(TextColumn\TextColumnSize::Large),

                TextColumn::make('language.name')
                    ->label('Bahasa')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_pinned')
                    ->label('Pin')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Publish')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
                TernaryFilter::make('is_pinned')->label('Pin'),
                \Filament\Tables\Filters\SelectFilter::make('language_id')
                    ->label('Bahasa')
                    ->relationship('language', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VideoInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'view' => ViewVideo::route('/{record}'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }

    private static function extractYoutubeId(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $patterns = [
            '~youtu\.be/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/watch\?v=([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/embed/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/shorts/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/live/([a-zA-Z0-9_-]{11})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $params);
            if (! empty($params['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $params['v'])) {
                return $params['v'];
            }
        }

        return null;
    }
}
