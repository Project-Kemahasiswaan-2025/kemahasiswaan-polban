<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrmawaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StudentOrganization::where('is_active', true);

        // Filter by category (parent) or show root organizations
        if ($request->has('category') && $request->category) {
            // Show children of the selected category
            $query->where('is_group', false)
                ->whereHas('parent', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
        } else {
            // Show root organizations (parent_id is null)
            $query->whereNull('parent_id');
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $organizations = $query->orderBy('sort_order')
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'slug' => $org->slug,
                    'category' => $org->parent ? $org->parent->slug : null,
                    'description' => $org->excerpt,
                    'logo' => $org->logo ? Storage::url($org->logo) : null,
                    'is_group' => $org->is_group,
                    'contact' => [
                        'email' => null,
                        'instagram' => null,
                    ],
                ];
            });

        return response()->json([
            'data' => $organizations
        ]);
    }

    public function groups(): JsonResponse
    {
        $groups = StudentOrganization::whereNull('parent_id')
            ->where('is_active', true)
            ->where('is_group', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'slug' => $group->slug,
                    'excerpt' => $group->excerpt,
                ];
            });

        return response()->json([
            'data' => $groups
        ]);
    }
}
