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
        static::saving(function ($download) {
            if ($download->isDirty('file_path') && $download->file_path) {
                // Ensure we are looking at the public disk as configured in Filament
                $path = $download->file_path;
                if (Storage::disk('public')->exists($path)) {
                    $download->file_size = Storage::disk('public')->size($path);

                    // Try to get mime type, fallback to extension
                    $mime = Storage::disk('public')->mimeType($path);
                    $download->file_type = $mime ?? pathinfo($path, PATHINFO_EXTENSION);
                }
            }
        });
    }

    protected $fillable = [
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

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function downloadable(): MorphTo
    {
        return $this->morphTo();
    }
}
