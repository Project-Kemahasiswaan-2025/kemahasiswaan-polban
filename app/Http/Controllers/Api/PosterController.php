<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PosterController extends Controller
{
    public function index(): JsonResponse
    {
        $categoryId = request()->get('category_id');

        $categories = Category::query()
            ->ofType('poster')
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            });

        $postersQuery = \App\Models\Poster::query()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        if ($categoryId) {
            $postersQuery->where('category_id', $categoryId);
        } else if ($categories->count() > 0) {
            // Default to first category if none provided
            $postersQuery->where('category_id', $categories->first()['id']);
            $categoryId = $categories->first()['id'];
        }

        $posters = $postersQuery->get()->map(function ($poster) {
            return [
                'id' => $poster->id,
                'title' => $poster->title,
                'image_url' => $poster->image_path ? Storage::url($poster->image_path) : null,
                'created_at' => $poster->created_at ? $poster->created_at->format('d M Y') : null,
            ];
        });

        return response()->json([
            'categories' => $categories,
            'posters' => $posters,
            'active_category_id' => (int) $categoryId,
        ]);
    }
}
