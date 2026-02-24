<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Add new columns to competition_threads ---
        Schema::table('competition_threads', function (Blueprint $table) {
            $table->string('status', 30)->default('draft')->after('sort_order');
            $table->string('post_url', 500)->nullable()->after('announcement_content');
            $table->string('registration_url', 500)->nullable()->after('post_url');
            $table->string('guidebook_url', 500)->nullable()->after('registration_url');
            $table->string('contact_info')->nullable()->after('guidebook_url');
            $table->string('location')->nullable()->after('contact_info');
        });

        // --- Create competition_timelines table ---
        Schema::create('competition_timelines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competition_thread_id')
                ->constrained('competition_threads')
                ->cascadeOnDelete();

            $table->string('label');
            $table->date('date');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['competition_thread_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_timelines');

        Schema::table('competition_threads', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'post_url',
                'registration_url',
                'guidebook_url',
                'contact_info',
                'location',
            ]);
        });
    }
};
