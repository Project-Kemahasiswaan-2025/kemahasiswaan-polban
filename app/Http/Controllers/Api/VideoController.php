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
        $videos = Video::where('is_active', true)
            ->when($request->category_id, fn($query) => $query->where('category_id', $request->category_id))
            ->where(function ($query) {
                $query->whereNull('active_from')
                    ->orWhere('active_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('active_to')
                    ->orWhere('active_to', '>=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('sort_order')
            ->get()
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
            'data' => $videos
        ]);
    }
}
