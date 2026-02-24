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
        Schema::create('competition_threads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competition_id')
                ->nullable()
                ->constrained('competitions')
                ->nullOnDelete();

            $table->foreignId('poster_id')
                ->nullable()
                ->constrained('posters')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();

            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->string('custom_image')->nullable();

            $table->longText('announcement_content')->nullable(); // For winners announcement

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_threads');
    }
};
