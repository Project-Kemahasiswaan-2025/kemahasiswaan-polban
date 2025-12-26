<?php

use App\Models\Language;

if (!function_exists('activeLanguage')) {
    /**
     * Get the current active language from session or default
     *
     * @return Language|null
     */
    function activeLanguage(): ?Language
    {
        $languageId = session('active_language_id');
        
        if ($languageId) {
            $language = Language::find($languageId);
            if ($language && $language->is_active) {
                return $language;
            }
        }
        
        // Return default language if session not set or language not found
        return Language::active()->default()->first() ?? Language::active()->first();
    }
}

if (!function_exists('setActiveLanguage')) {
    /**
     * Set the active language in session
     *
     * @param int|Language $language
     * @return void
     */
    function setActiveLanguage(int|Language $language): void
    {
        $languageId = $language instanceof Language ? $language->id : $language;
        session(['active_language_id' => $languageId]);
        
        // Also set app locale
        $lang = Language::find($languageId);
        if ($lang) {
            app()->setLocale($lang->code);
        }
    }
}
