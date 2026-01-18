<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningTextConfig extends Model
{
    protected $fillable = [
        'icon_text',
        'separator_text',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
