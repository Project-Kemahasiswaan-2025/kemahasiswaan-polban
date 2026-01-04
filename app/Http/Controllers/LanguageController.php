<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $language = Language::where('code', $locale)->where('is_active', true)->first();
        
        if ($language) {
            Session::put('active_language_id', $language->id);
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
