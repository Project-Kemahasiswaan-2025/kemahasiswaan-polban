<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use SoftDeletes;
    protected $fillable = [
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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Competition $record) {
            if ($record->isForceDeleting()) {
                $record->children()->withTrashed()->get()->each(fn($c) => $c->forceDelete());
            } else {
                $record->children()->get()->each(fn($c) => $c->delete());
            }
        });

        static::restoring(function (Competition $record) {
            $record->children()->withTrashed()->get()->each->restore();
        });
    }
}
