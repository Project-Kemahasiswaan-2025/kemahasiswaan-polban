<?php

namespace App\View\Composers;

use App\Models\StudentOrganization;
use App\Models\Page;
use App\Models\Service;
use App\Models\DocumentShortcut;
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

        $services = Service::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        $contactSettings = \App\Models\ContactSetting::getSettings();

        $documentShortcuts = DocumentShortcut::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('menu');

        $view->with('ormawaGroups', $ormawaGroups);
        $view->with('profilePages', $profilePages);
        $view->with('services', $services);
        $view->with('contactSettings', $contactSettings);
        $view->with('documentShortcuts', $documentShortcuts);
    }
}
