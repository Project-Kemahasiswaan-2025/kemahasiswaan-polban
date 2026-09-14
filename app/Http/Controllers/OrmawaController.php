<?php

namespace App\Http\Controllers;

use App\Models\StudentOrganization;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrmawaController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');

        // All active parent groups (e.g. HMJ, UKM) for filter dropdown and navigation
        $parentGroups = StudentOrganization::whereNull('parent_id')
            ->where('is_active', true)
            ->where('is_group', true)
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = StudentOrganization::whereNull('parent_id')
                ->where('is_active', true)
                ->where('is_group', true)
                ->where('slug', $categorySlug)
                ->first();
        }

        $query = StudentOrganization::where('is_active', true);

        if ($selectedCategory) {
            // Mode 2: Children organizations belonging to this category
            $query->where('parent_id', $selectedCategory->id);
        } else {
            // Mode 1: Parent/root level organizations (MPM, BEM, HMJ, UKM)
            $query->whereNull('parent_id');
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $organizations = $query->orderBy('sort_order')->get();

        return view('pages.ormawa.index', compact(
            'organizations',
            'parentGroups',
            'selectedCategory',
            'search'
        ));
    }

    public function show(string $slug): View
    {
        $organization = StudentOrganization::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.ormawa.show', compact('organization'));
    }
}
