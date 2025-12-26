<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get language from session
        $languageId = session('active_language_id');

        if ($languageId) {
            $language = Language::find($languageId);
            if ($language && $language->is_active) {
                app()->setLocale($language->code);

                return $next($request);
            }
        }

        // Set default language if not set or invalid
        $defaultLanguage = Language::active()->default()->first() ?? Language::active()->first();
        if ($defaultLanguage) {
            session(['active_language_id' => $defaultLanguage->id]);
            app()->setLocale($defaultLanguage->code);
        }

        return $next($request);
    }
}
