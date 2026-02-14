<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index()
    {
        $categories = Category::where('type', 'download')
            ->active()
            ->with(['downloads' => fn($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('pages.downloads.index', compact('categories'));
    }
}
