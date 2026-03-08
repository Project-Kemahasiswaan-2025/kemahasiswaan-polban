<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $download = Download::where('is_active', true)
            ->findOrFail($id);

        $categories = Category::where('type', 'download')
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('pages.downloads.show', compact('download', 'categories'));
    }

    public function download($hash)
    {
        $download = Download::where('is_active', true)
            ->where('hash', $hash)
            ->firstOrFail();

        $path = $download->file_path;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // Get extension from storage path
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Clean name (remove extension if user added it to name field)
        $cleanName = preg_replace('/\.' . preg_quote($extension, '/') . '$/i', '', $download->name);
        $filename = $cleanName . '.' . $extension;

        return Storage::disk('public')->download($path, $filename);
    }
}
