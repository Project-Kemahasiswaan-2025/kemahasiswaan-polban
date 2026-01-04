<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Competition::where('is_active', true)
            ->where('is_group', false);

        // Filter by category (parent)
        if ($request->has('category') && $request->category) {
            $query->whereHas('parent', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by status
        if ($request->has('status')) {
            // For now, all active competitions are considered "active"
            // This could be extended with a deadline field
        }

        $competitions = $query->orderBy('sort_order')
            ->get()
            ->map(function ($comp) {
                return [
                    'id' => $comp->id,
                    'name' => $comp->name,
                    'category' => $comp->parent ? $comp->parent->slug : null,
                    'description' => strip_tags($comp->content ?? ''),
                    'link' => $comp->url,
                    'is_external' => $comp->url_target === '_blank',
                    'deadline' => null, // Could be added to model if needed
                    'status' => 'active',
                ];
            });

        return response()->json([
            'data' => $competitions
        ]);
    }
}
