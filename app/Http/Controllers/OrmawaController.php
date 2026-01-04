<?php

namespace App\Http\Controllers;

use App\Models\StudentOrganization;
use Illuminate\View\View;

class OrmawaController extends Controller
{
    public function index(): View
    {
        return view('pages.ormawa.index');
    }

    public function show(string $slug): View
    {
        $organization = StudentOrganization::where('slug', $slug)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->firstOrFail();

        return view('pages.ormawa.show', compact('organization'));
    }
}
