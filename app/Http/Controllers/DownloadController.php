<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', 'download')
            ->active()
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = null;
        if ($request->has('category')) {
            $selectedCategory = $categories->where('slug', $request->category)->first();
        }

        $downloads = \App\Models\Download::query()
            ->where('is_active', true)
            ->whereNotNull('category_id')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory->id);
            })
            ->orderBy('sort_order')
            ->paginate(10);

        return view('pages.downloads.index', compact('categories', 'downloads', 'selectedCategory'));
    }
}
