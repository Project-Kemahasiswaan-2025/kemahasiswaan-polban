<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(): JsonResponse
    {
        $videos = Video::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'video_url' => $video->youtube_url,
                    'thumbnail' => $video->thumbnail_url ? Storage::url($video->thumbnail_url) : null,
                    'is_featured' => $video->is_pinned,
                ];
            });

        return response()->json([
            'data' => $videos
        ]);
    }
}
