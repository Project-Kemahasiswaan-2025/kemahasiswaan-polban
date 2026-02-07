<?php

namespace App\Filament\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $selectedLanguage;

    public function mount()
    {
        $this->selectedLanguage = App::getLocale();
    }

    public function updatedSelectedLanguage($value)
    {
        if (in_array($value, ['en', 'id'])) {
            Session::put('locale', $value);
            App::setLocale($value);

            // Refresh halaman untuk apply filter
            return redirect()->to(request()->header('Referer') ?: '/admin');
        }
    }

    public function render()
    {
        return view('filament.livewire.language-switcher', [
            'languages' => [
                ['id' => 'id', 'name' => 'Bahasa Indonesia'],
                ['id' => 'en', 'name' => 'English'],
            ],
        ]);
    }
}
