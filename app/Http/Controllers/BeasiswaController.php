<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BeasiswaController extends Controller
{
    public function index(): View
    {
        return view('pages.beasiswa.index');
    }
}
