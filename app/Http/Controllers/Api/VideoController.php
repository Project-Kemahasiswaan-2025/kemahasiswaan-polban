<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $categoryId = $request->get('category_id');

        $categories = \App\Models\Category::query()
            ->ofType('video')
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            });

        $videosQuery = Video::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('active_from')
                    ->orWhere('active_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('active_to')
                    ->orWhere('active_to', '>=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('sort_order');

        if ($categoryId) {
            $videosQuery->where('category_id', $categoryId);
        } else if ($categories->count() > 0) {
            // Default to first category if none provided for filtered view
            // However, for the initial "Featured" view, we might want all pinned videos?
            // Actually, matching poster pattern: default to first category
            $videosQuery->where('category_id', $categories->first()['id']);
            $categoryId = $categories->first()['id'];
        }

        $videos = $videosQuery->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'video_url' => $video->youtube_url,
                    'thumbnail' => $video->thumbnail_url
                        ? (filter_var($video->thumbnail_url, FILTER_VALIDATE_URL) ? $video->thumbnail_url : Storage::url($video->thumbnail_url))
                        : null,
                    'is_featured' => $video->is_pinned,
                ];
            });

        return response()->json([
            'categories' => $categories,
            'videos' => $videos,
            'active_category_id' => (int) $categoryId,
        ]);
    }
}
