<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->string('title', 180);
            $table->text('description')->nullable();

            $table->string('youtube_url')->nullable();
            $table->string('youtube_id', 32)->nullable();

            $table->string('thumbnail_url')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'published_at']);
            $table->index(['is_pinned', 'sort_order']);
            $table->index('youtube_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
