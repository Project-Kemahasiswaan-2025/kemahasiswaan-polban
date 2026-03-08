<?php

namespace App\Http\Controllers;

use App\Models\Page;

class ProfileController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->where('section', 'profile')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.profile.show', compact('page'));
    }
}
