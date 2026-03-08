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

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan & Bantuan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAtSymbol;

    protected static ?string $navigationLabel = 'Kontak & Sosmed';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Kontak')
                ->description('Konfigurasi alamat, email, dan nomor telepon resmi')
                ->schema([
                    Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('email')
                            ->label('Email Resmi')
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->required(),
                    ]),
                ]),

            Section::make('Media Sosial & Maps')
                ->description('Tautan media sosial dan Google Maps embed URL')
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
                        ->helperText('Masukkan URL src dari iframe Google Maps')
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
