<?php

namespace App\Filament\Widgets;

use App\Models\Language;
use Filament\Widgets\Widget;

class LanguageSwitcher extends Widget
{
    protected string $view = 'filament.widgets.language-switcher';

    protected int|string|array $columnSpan = 'full';

    public function switchLanguage(int $languageId): void
    {
        $language = Language::findOrFail($languageId);

        if ($language->is_active) {
            setActiveLanguage($language);

            // Redirect to refresh the page with new locale
            $this->redirect(request()->header('Referer') ?: '/admin');
        }
    }

    public function getCurrentLanguage(): ?Language
    {
        return activeLanguage();
    }

    public function getAvailableLanguages()
    {
        return Language::active()->get();
    }
}
