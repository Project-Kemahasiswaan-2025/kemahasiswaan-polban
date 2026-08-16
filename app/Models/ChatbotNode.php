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
     * Smart URL resolver: Converts relative paths like '/ormawa' or 'beasiswa' to full base URLs.
     */
    public function getResolvedActionUrl(): ?string
    {
        if (empty($this->action_url)) {
            return null;
        }

        $url = trim($this->action_url);

        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        return url($url);
    }
}
