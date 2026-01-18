<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use App\Models\RunningTextConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class RunningTextController extends Controller
{
    public function index(): JsonResponse
    {
        // Get active language from session or default
        $activeLanguageId = Session::get('active_language_id');
        
        if (!$activeLanguageId) {
            // Fallback to default language
            $defaultLanguage = \App\Models\Language::active()->default()->first();
            if (!$defaultLanguage) {
                $defaultLanguage = \App\Models\Language::active()->first();
            }
            $activeLanguageId = $defaultLanguage?->id;
        }

        // Get config (use first record or create default)
        $config = RunningTextConfig::first();
        if (!$config) {
            $config = new RunningTextConfig([
                'icon_text' => '🔊',
                'separator_text' => '•',
                'is_enabled' => true
            ]);
        }

        // Get active running texts for the active language
        $runningTexts = RunningText::where('language_id', $activeLanguageId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($text) {
                return [
                    'id' => $text->id,
                    'content' => $text->content,
                    'duration_seconds' => $text->duration_seconds,
                ];
            });

        return response()->json([
            'config' => [
                'icon_text' => $config->icon_text,
                'separator_text' => $config->separator_text,
                'is_enabled' => $config->is_enabled,
            ],
            'data' => $runningTexts
        ]);
    }
}
