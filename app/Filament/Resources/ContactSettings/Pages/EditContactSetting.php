<?php

namespace App\Filament\Resources\ContactSettings\Pages;

use App\Filament\Resources\ContactSettings\ContactSettingResource;
use App\Models\ContactSetting;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditContactSetting extends EditRecord
{
    protected static string $resource = ContactSettingResource::class;

    public function mount($record = null): void
    {
        $record = ContactSetting::first();

        if (!$record) {
            $record = ContactSetting::create([
                'address' => 'Jl. Gegerkalong Hilir, Bandung',
                'email' => 'kemahasiswaan@polban.ac.id',
                'phone' => '(022) 1234567',
            ]);
        }

        parent::mount($record->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
