<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasPublishWindow
{
    public function scopeActiveNow(Builder $query, ?Carbon $now = null): Builder
    {
        $now = $now ?: now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('active_from')
                    ->orWhere('active_from', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('active_to')
                    ->orWhere('active_to', '>=', $now);
            });
    }

    public function scopeOrderedForDisplay(Builder $query): Builder
    {
        return $query->orderByDesc('is_pinned')
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function scopeForPlacement(Builder $query, string $placementKey): Builder
    {
        return $query->where('placement_key', $placementKey);
    }
}
