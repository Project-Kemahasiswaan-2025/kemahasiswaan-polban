<?php

namespace App\Models;

use App\Models\Traits\HasPublishWindow;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasPublishWindow;

    protected $fillable = [
        'title',
        'image_path',
        'url',
        'url_target',
        'is_active',
        'active_from',
        'active_to',
        'is_pinned',
        'sort_order',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_pinned'   => 'boolean',
        'active_from' => 'datetime',
        'active_to'   => 'datetime',
    ];
}
