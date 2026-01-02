<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class OrmawaController extends Controller
{
    public function index(): View
    {
        return view('pages.ormawa.index');
    }
}
