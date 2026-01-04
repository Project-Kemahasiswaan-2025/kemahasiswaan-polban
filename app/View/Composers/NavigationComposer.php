<?php

namespace App\View\Composers;

use App\Models\StudentOrganization;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $ormawaGroups = StudentOrganization::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $view->with('ormawaGroups', $ormawaGroups);
    }
}
