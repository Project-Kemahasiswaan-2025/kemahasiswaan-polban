<?php

namespace App\Filament\Resources\ContactSettings;

use App\Filament\Resources\ContactSettings\Pages\EditContactSetting;
use App\Models\ContactSetting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContactSettingResource extends Resource
{
    protected static ?string $model = ContactSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAtSymbol;

    protected static ?int $navigationSort = 60;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.resources.contact_setting.nav_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.contact_setting.nav_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.contact_info'))
                ->description(__('filament.sections.contact_info_desc'))
                ->schema([
                    Textarea::make('address')
                        ->label(__('filament.fields.address'))
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('email')
                            ->label(__('filament.fields.official_email'))
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label(__('filament.fields.phone'))
                            ->required(),
                    ]),
                ]),

            Section::make(__('filament.sections.social_media'))
                ->description(__('filament.sections.social_media_desc'))
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->placeholder('https://instagram.com/...'),

                        TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->placeholder('https://facebook.com/...'),

                        TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->placeholder('https://twitter.com/...'),

                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://youtube.com/...'),
                    ]),

                    Textarea::make('maps_url')
                        ->label('Google Maps Embed URL')
                        ->helperText(__('filament.fields.maps_url_helper'))
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditContactSetting::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
