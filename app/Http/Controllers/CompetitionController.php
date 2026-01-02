<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(): View
    {
        return view('pages.competition.index');
    }
}
