<?php

namespace App\Traits\Database;

use Illuminate\Database\Schema\Blueprint;

trait HasPublishWindowColumns
{
    public function addPublishWindowColumns(Blueprint $table): void
    {
        $table->boolean('is_active')->default(true)->index();
        $table->dateTime('active_from')->nullable()->index();
        $table->dateTime('active_to')->nullable()->index();
        $table->boolean('is_pinned')->default(false)->index();
        $table->unsignedInteger('sort_order')->default(0)->index();
    }
}
