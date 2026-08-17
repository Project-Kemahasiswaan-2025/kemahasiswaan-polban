<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class ChatbotNode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'target_node_id',
        'title',
        'icon',
        'bot_response',
        'action_type',
        'module_key',
        'action_url',
        'action_label',
        'action_icon',
        'action_icon_position',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'parent_id' => 'integer',
        'target_node_id' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function activeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function targetNode(): BelongsTo
    {
        return $this->belongsTo(self::class, 'target_node_id');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get bot response text, supporting random synonym variations if multi-line / JSON array is set.
     */
    public function getRandomResponse(): string
    {
        if (empty($this->bot_response)) {
            return '';
        }

        $raw = trim($this->bot_response);

        // Check if JSON array
        if (str_starts_with($raw, '[') && str_ends_with($raw, ']')) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return (string) Arr::random($decoded);
            }
        }

        // Check multi-line variation separated by '---'
        if (str_contains($raw, '---')) {
            $variations = array_filter(array_map('trim', explode('---', $raw)));
            if (count($variations) > 0) {
                return (string) Arr::random($variations);
            }
        }

        return $raw;
    }

    /**
     * Smart URL resolver: Converts relative paths (e.g. '/kontak', '/ormawa', '/layanan') to full URLs,
     * with prefix awareness (e.g. '/kemahasiswaan/kontak') while remaining resilient if the prefix is changed/removed.
     */
    public static function resolveSmartUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // 1. If full HTTP/HTTPS URL, return as is
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        $path = '/' . ltrim($url, '/');

        // 2. Named route mapping for standard paths
        $routeMap = [
            '/kontak' => 'contact.index',
            '/ormawa' => 'ormawa.index',
            '/beasiswa' => 'beasiswa.index',
            '/kompetisi' => 'competition.index',
            '/unduhan' => 'download.index',
            '/' => 'home',
        ];

        if (isset($routeMap[$path]) && \Illuminate\Support\Facades\Route::has($routeMap[$path])) {
            return route($routeMap[$path]);
        }

        // 3. Dynamic Prefix Detection (e.g., /kemahasiswaan)
        if (\Illuminate\Support\Facades\Route::has('home')) {
            $homeUrl = route('home');
            $parsedHomePath = parse_url($homeUrl, PHP_URL_PATH) ?? '';
            $prefix = trim($parsedHomePath, '/');

            if ($prefix && !str_starts_with($path, '/' . $prefix)) {
                return url('/' . $prefix . $path);
            }
        }

        return url($path);
    }

    public function getResolvedActionUrl(): ?string
    {
        return self::resolveSmartUrl($this->action_url);
    }
}
