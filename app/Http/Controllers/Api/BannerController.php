<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
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
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'image_url' => $banner->image_path ? Storage::url($banner->image_path) : null,
                    'link' => $banner->url,
                    'order' => $banner->sort_order,
                    'is_active' => $banner->is_active,
                ];
            });

        return response()->json([
            'data' => $banners
        ]);
    }
}
