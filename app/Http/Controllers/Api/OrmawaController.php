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
        $query = StudentOrganization::where('is_active', true)
            ->where('is_group', false);

        // Filter by category (parent)
        if ($request->has('category')) {
            $query->whereHas('parent', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $organizations = $query->orderBy('sort_order')
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'category' => $org->parent ? $org->parent->slug : null,
                    'description' => $org->excerpt,
                    'logo' => $org->logo ? Storage::url($org->logo) : null,
                    'contact' => [
                        'email' => null, // Could be extracted from content or added to model
                        'instagram' => null,
                    ],
                ];
            });

        return response()->json([
            'data' => $organizations
        ]);
    }
}
