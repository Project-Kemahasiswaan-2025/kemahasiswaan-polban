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

        $profilePages = Page::query()
            ->where('section', 'profile')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug']);

        $view->with('ormawaGroups', $ormawaGroups);
        $view->with('profilePages', $profilePages);
    }
}
