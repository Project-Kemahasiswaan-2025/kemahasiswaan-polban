<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentOrganization extends Model
{
    use SoftDeletes;
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
        'is_group' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::deleting(function (StudentOrganization $record) {
            // Cascade to children: handle both soft and force delete
            if ($record->isForceDeleting()) {
                $record->children()->withTrashed()->get()->each(fn($c) => $c->forceDelete());
            } else {
                // Rename slug to allow re-use of the original slug
                $record->slug .= '-deleted-' . now()->timestamp;
                $record->save();

                $record->children()->get()->each(fn($c) => $c->delete());
            }
        });

        static::restoring(function (StudentOrganization $record) {
            $record->children()->withTrashed()->get()->each->restore();
        });
    }
}
