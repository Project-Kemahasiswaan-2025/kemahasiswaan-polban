<?php

namespace App\Filament\Resources\RunningTextConfigs;

use App\Filament\Resources\RunningTextConfigs\Pages\EditRunningTextConfig;
use App\Models\RunningTextConfig;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RunningTextConfigResource extends Resource
{
    protected static ?string $model = RunningTextConfig::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.resources.running_text_config.nav_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.running_text_config.nav_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make(__('filament.sections.running_text_config'))
                ->description(__('filament.sections.running_text_config_desc'))
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('icon_text')
                            ->label('Icon Text')
                            ->default('🔊')
                            ->maxLength(10)
                            ->required()
                            ->helperText('Text or emoji to display at the left of the ticker'),

                        TextInput::make('separator_text')
                            ->label('Separator Text')
                            ->default('•')
                            ->maxLength(10)
                            ->required()
                            ->helperText('Text or symbol to separate multiple running texts'),
                    ]),

                    Toggle::make('is_enabled')
                        ->label('Enable Running Text')
                        ->default(true)
                        ->helperText('Show or hide the running text ticker on the home page'),
                ])
                ->columns(1),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditRunningTextConfig::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
