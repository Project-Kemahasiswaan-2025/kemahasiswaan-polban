<?php

namespace App\Filament\Resources\RunningTextConfigs\Pages;

use App\Filament\Resources\RunningTextConfigs\RunningTextConfigResource;
use App\Models\RunningTextConfig;
use Filament\Resources\Pages\EditRecord;

class EditRunningTextConfig extends EditRecord
{
    protected static string $resource = RunningTextConfigResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get the first config record or create a new one
        $config = RunningTextConfig::first();
        
        if (!$config) {
            $config = RunningTextConfig::create([
                'icon_text' => '🔊',
                'separator_text' => '•',
                'is_enabled' => true,
            ]);
        }

        return $config->toArray();
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    public function mount(int | string $record = null): void
    {
        // Always use the first config record
        $config = RunningTextConfig::first();
        
        if (!$config) {
            $config = RunningTextConfig::create([
                'icon_text' => '🔊',
                'separator_text' => '•',
                'is_enabled' => true,
            ]);
        }

        $this->record = $config;

        $this->fillForm();
    }
}
