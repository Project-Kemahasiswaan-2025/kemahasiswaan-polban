<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationHighlight extends Model
{
    protected $fillable = [
        'student_organization_id',
        'title',
        'description',
        'image',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function studentOrganization(): BelongsTo
    {
        return $this->belongsTo(StudentOrganization::class);
    }
}
