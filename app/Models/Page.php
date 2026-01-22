<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'language_id',
        'section',
        'title',
        'slug',
        'sort_order',
        'document_path',
        'document_type',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
