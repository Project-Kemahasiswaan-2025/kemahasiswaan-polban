<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionTimeline extends Model
{
    protected $fillable = [
        'competition_thread_id',
        'label',
        'date',
        'sort_order',
    ];

    protected $casts = [
        'date' => 'date',
        'sort_order' => 'integer',
    ];

    public function competitionThread(): BelongsTo
    {
        return $this->belongsTo(CompetitionThread::class);
    }
}
