<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'is_active',
        'is_pinned',
        'active_from',
        'active_to',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
        'category_id' => 'integer',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public static function extractYoutubeId(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $patterns = [
            '~youtu\.be/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/watch\?v=([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/embed/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/shorts/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/live/([a-zA-Z0-9_-]{11})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $params);
            if (! empty($params['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $params['v'])) {
                return $params['v'];
            }
        }

        return null;
    }
}
