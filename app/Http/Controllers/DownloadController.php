<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            ->when($request->query('search'), function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('pages.downloads.index', compact('categories', 'downloads', 'selectedCategory'));
    }

    public function show($id): View
    {
        $download = \App\Models\Download::where('is_active', true)
            ->findOrFail($id);

        $categories = Category::where('type', 'download')
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('pages.downloads.show', compact('download', 'categories'));
    }
}
