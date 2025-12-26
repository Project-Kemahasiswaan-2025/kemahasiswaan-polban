<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentOrganization extends Model
{
    protected $fillable = [
        'parent_id',
        'is_group',
        'name',
        'slug',
        'sort_order',
        'is_active',
        'logo',
        'cover_image',
        'excerpt',
        'content',
        'cta_label',
        'cta_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_group'  => 'boolean',
    ];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }
}
