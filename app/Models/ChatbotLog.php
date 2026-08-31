<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'node_id',
        'user_action',
        'bot_response_summary',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatbotSession::class, 'session_id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id');
    }
}
