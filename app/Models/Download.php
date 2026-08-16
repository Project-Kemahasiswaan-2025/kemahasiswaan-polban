<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // Handle metadata for local file vs external link
            if ($download->type === 'link') {
                $download->file_path = null;

                if ($download->external_url && (empty($download->file_type) || $download->file_type === 'link')) {
                    $analysis = self::analyzeUrl($download->external_url);
                    if (!empty($analysis['file_size'])) {
                        $download->file_size = $analysis['file_size'];
                    }
                    if (!empty($analysis['file_type'])) {
                        $download->file_type = $analysis['file_type'];
                    }
                }
            } else {
                $download->type = 'file';
                $download->external_url = null;

                if ($download->isDirty('file_path') && $download->file_path) {
                    $path = $download->file_path;
                    if (Storage::disk('public')->exists($path)) {
                        $download->file_size = Storage::disk('public')->size($path);
                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $download->file_type = $extension ?: 'file';
                    }
                }
            }

            // Ensure hash is set for existing records if missing
            if ($download->id && !$download->getRawOriginal('hash')) {
                $download->hash = self::generateUniqueHash($download->id);
            }
        });
    }

    /**
     * Analyze an external URL using HTTP HEAD / Range GET to detect metadata without downloading/storing a copy.
     */
    public static function analyzeUrl(string $url): array
    {
        $result = [
            'file_size' => null,
            'file_type' => 'link',
            'suggested_name' => null,
        ];

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $result;
        }

        try {
            $userAgents = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

            // Try HTTP HEAD request first
            $response = Http::timeout(5)
                ->withUserAgent($userAgents)
                ->head($url);

            if (!$response->successful()) {
                // Fallback to GET with range header to only read initial bytes
                $response = Http::timeout(5)
                    ->withUserAgent($userAgents)
                    ->withHeaders(['Range' => 'bytes=0-1024'])
                    ->get($url);
            }

            if ($response->successful()) {
                // 1. File Size
                $contentLength = $response->header('Content-Length');
                if ($contentLength && is_numeric($contentLength)) {
                    $result['file_size'] = (int) $contentLength;
                } else {
                    // Check Content-Range (e.g. bytes 0-1024/524288)
                    $contentRange = $response->header('Content-Range');
                    if ($contentRange && preg_match('/\/(\d+)$/', $contentRange, $matches)) {
                        $result['file_size'] = (int) $matches[1];
                    }
                }

                // 2. Content Type & Extension Detection
                $contentType = strtolower($response->header('Content-Type') ?? '');
                $contentDisposition = $response->header('Content-Disposition') ?? '';

                if ($contentDisposition && preg_match('/filename=["\']?([^"\';]+)["\']?/', $contentDisposition, $matches)) {
                    $filename = trim($matches[1]);
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if ($ext) {
                        $result['file_type'] = $ext;
                    }
                    $result['suggested_name'] = (string) Str::of(pathinfo($filename, PATHINFO_FILENAME))->replace(['-', '_'], ' ')->title();
                }

                if ($result['file_type'] === 'link' && $contentType) {
                    $mimeMap = [
                        'application/pdf' => 'pdf',
                        'application/msword' => 'doc',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                        'application/vnd.ms-excel' => 'xls',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                        'application/vnd.ms-powerpoint' => 'ppt',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
                        'application/zip' => 'zip',
                        'application/x-rar-compressed' => 'rar',
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/svg+xml' => 'svg',
                    ];

                    foreach ($mimeMap as $mime => $ext) {
                        if (str_contains($contentType, $mime)) {
                            $result['file_type'] = $ext;
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently fallback on timeout, redirect loop, cloudflare block, etc.
        }

        // Fallback file_type detection from URL path if mime check was ambiguous
        if ($result['file_type'] === 'link') {
            $parsedPath = parse_url($url, PHP_URL_PATH);
            if ($parsedPath) {
                $ext = strtolower(pathinfo($parsedPath, PATHINFO_EXTENSION));
                if (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'png', 'svg', 'csv', 'txt'])) {
                    $result['file_type'] = $ext;
                }

                if (!$result['suggested_name']) {
                    $filename = pathinfo($parsedPath, PATHINFO_FILENAME);
                    if ($filename && strlen($filename) > 3) {
                        $result['suggested_name'] = (string) Str::of(urldecode($filename))->replace(['-', '_'], ' ')->title();
                    }
                }
            }
        }

        // Detect Google Drive / Google Docs links
        if (str_contains($url, 'drive.google.com') || str_contains($url, 'docs.google.com')) {
            if ($result['file_type'] === 'link') {
                $result['file_type'] = 'gdrive';
            }
        }

        return $result;
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
        'type',
        'external_url',
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

