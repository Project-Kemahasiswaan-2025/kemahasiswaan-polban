<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompetitionThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CompetitionThread::with(['competition.parent', 'poster', 'timelines'])
            ->where('is_active', true);

        // Filter by category (parent competition slug)
        if ($request->has('category') && $request->category) {
            $query->whereHas('competition.parent', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $paginator = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        $threads = collect($paginator->items())->map(function ($thread) {
            // Determine image URL: Poster priority, then custom image
            $imageUrl = null;
            if ($thread->poster && $thread->poster->image_path) {
                $imageUrl = asset('storage/' . $thread->poster->image_path);
            } elseif ($thread->custom_image) {
                $imageUrl = asset('storage/' . $thread->custom_image);
            }

            // Format registration range
            $regRange = null;
            if ($thread->registration_start && $thread->registration_end) {
                $regRange = $thread->registration_start->format('d M') . ' - ' . $thread->registration_end->format('d M Y');
            } elseif ($thread->registration_end) {
                $regRange = 's.d. ' . $thread->registration_end->format('d M Y');
            }

            return [
                'id' => $thread->id,
                'title' => $thread->title,
                'slug' => $thread->slug,
                'competition_name' => $thread->competition ? $thread->competition->name : null,
                'category_name' => ($thread->competition && $thread->competition->parent) ? $thread->competition->parent->name : 'Lainnya',
                'status' => $thread->status,
                'image_url' => $imageUrl,
                'registration_range' => $regRange,
                'post_url' => $thread->post_url,
                'registration_url' => $thread->registration_url,
                'guidebook_url' => $thread->guidebook_url,
                'contact_info' => $thread->contact_info,
                'location' => $thread->location,
                'content' => $thread->content,
                'timelines' => $thread->timelines->map(function ($t) {
                    return [
                        'label' => $t->label,
                        'date' => $t->date ? $t->date->format('d M Y') : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $threads,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}
