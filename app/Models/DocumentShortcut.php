<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentShortcut extends Model
{
    protected $fillable = [
        'menu',
        'title',
        'category_id',
        'download_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'category_id' => 'integer',
        'download_id' => 'integer',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function download(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Download::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->download_id) {
            return route('download.show', $this->download_id);
        }

        if ($this->category) {
            return route('download.index', ['category' => $this->category->slug]);
        }

        return route('download.index');
    }
}
