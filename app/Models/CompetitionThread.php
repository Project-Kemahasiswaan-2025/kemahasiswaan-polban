<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionThread extends Model
{
    protected $fillable = [
        'competition_id',
        'poster_id',
        'title',
        'slug',
        'content',
        'registration_start',
        'registration_end',
        'custom_image',
        'announcement_content',
        'is_active',
        'is_featured',
        'status',
        'post_url',
        'registration_url',
        'guidebook_url',
        'contact_info',
        'location',
    ];

    protected $casts = [
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(Poster::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(CompetitionTimeline::class)->orderBy('sort_order');
    }
}
