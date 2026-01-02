<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'language_id',
        'parent_id',
        'name',
        'slug',
        'is_group',
        'is_active',
        'sort_order',
        'cover_image',
        'url',
        'url_target',
        'content',
    ];

    protected $casts = [
        'is_group' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
