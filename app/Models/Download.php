<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::created(function ($download) {
            // Persist the unique hash based on ID
            $hash = self::generateUniqueHash($download->id);
            $download->updateQuietly(['hash' => $hash]);
        });

        static::saving(function ($download) {
            // Handle metadata
            if ($download->isDirty('file_path') && $download->file_path) {
                $path = $download->file_path;
                if (Storage::disk('public')->exists($path)) {
                    $download->file_size = Storage::disk('public')->size($path);
                    $mime = Storage::disk('public')->mimeType($path);
                    $download->file_type = $mime ?? pathinfo($path, PATHINFO_EXTENSION);
                }
            }

            // Ensure hash is set for existing records if missing
            if ($download->id && !$download->getRawOriginal('hash')) {
                $download->hash = self::generateUniqueHash($download->id);
            }
        });
    }

    public static function generateUniqueHash($id): string
    {
        $hash = substr(md5($id . config('app.key')), 0, 8);
        $originalHash = $hash;
        $counter = 1;

        while (self::where('hash', $hash)->where('id', '!=', $id)->exists()) {
            $hash = substr(md5($originalHash . $counter++), 0, 8);
        }

        return $hash;
    }

    protected $fillable = [
        'hash',
        'name',
        'file_path',
        'file_type',
        'file_size',
        'downloadable_id',
        'downloadable_type',
        'category_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'file_size' => 'integer',
        'category_id' => 'integer',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return route('download.file', $this->hash);
    }

    public function getHashAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        if ($this->id) {
            return substr(md5($this->id . config('app.key')), 0, 8);
        }

        return '';
    }

    public static function findByHash(string $hash): ?self
    {
        return self::where('hash', $hash)->first();
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function downloadable(): MorphTo
    {
        return $this->morphTo();
    }
}
