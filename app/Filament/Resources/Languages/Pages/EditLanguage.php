<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use App\Models\Language;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Language $record) {
                    // Prevent deletion of default language
                    if ($record->is_default) {
                        throw new \Exception('Cannot delete the default language.');
                    }

                    // Ensure at least one active language remains
                    if ($record->is_active && Language::active()->count() <= 1) {
                        throw new \Exception('At least one active language must remain.');
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If setting this as default, unset other defaults
        if ($data['is_default'] ?? false) {
            Language::where('id', '!=', $this->record->id)
                ->update(['is_default' => false]);
        }

        return $data;
    }
}
