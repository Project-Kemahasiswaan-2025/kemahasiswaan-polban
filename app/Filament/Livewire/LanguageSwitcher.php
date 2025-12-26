<?php

namespace App\Filament\Livewire;

use App\Models\Language;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $selectedLanguage;

    public function mount()
    {
        $this->selectedLanguage = activeLanguage()?->id;
    }

    public function updatedSelectedLanguage($value)
    {
        $language = Language::find($value);

        if ($language && $language->is_active) {
            setActiveLanguage($language);

            // Refresh halaman untuk apply filter
            return redirect()->to(request()->header('Referer') ?: '/admin');
        }
    }

    public function render()
    {
        return view('filament.livewire.language-switcher', [
            'languages' => Language::active()->get(),
        ]);
    }
}
