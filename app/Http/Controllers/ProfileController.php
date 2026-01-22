<?php

namespace App\Http\Controllers;

use App\Models\Page;

class ProfileController extends Controller
{
    public function show(string $slug)
    {
        $langId = activeLanguage()?->id;

        $page = Page::query()
            ->where('section', 'profile')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where(function ($q) use ($langId) {
                // kalau lang null, otomatis fallback ke default (null)
                if ($langId) {
                    $q->where('language_id', $langId)->orWhereNull('language_id');
                    return;
                }
                $q->whereNull('language_id');
            })
            ->orderByRaw('language_id IS NULL') // prioritaskan yang spesifik
            ->firstOrFail();

        return view('pages.profile.show', compact('page'));
    }
}
