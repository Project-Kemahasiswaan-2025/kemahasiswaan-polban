<?php

namespace App\View\Composers;

use App\Models\StudentOrganization;
use App\Models\Page;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $ormawaGroups = StudentOrganization::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $langId = activeLanguage()?->id;

        $profilePages = Page::query()
            ->where('section', 'profile')
            ->where('is_active', true)
            ->where(function ($q) use ($langId) {
                if ($langId) {
                    $q->where('language_id', $langId)->orWhereNull('language_id');
                    return;
                }
                $q->whereNull('language_id');
            })
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug']);

        $view->with('ormawaGroups', $ormawaGroups);
        $view->with('profilePages', $profilePages);
    }
}
