<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(): View
    {
        $categories = Competition::where('is_group', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.competition.index', compact('categories'));
    }
}
