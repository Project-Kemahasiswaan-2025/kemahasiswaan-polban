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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('language_id')
                ->nullable()
                ->constrained('languages')
                ->nullOnDelete();

            $table->string('section', 50)->index(); // profile, services, dll

            $table->string('title', 180);
            $table->string('slug', 200);

            $table->unsignedInteger('sort_order')->default(0);

            $table->string('document_path')->nullable(); // image / pdf
            $table->string('document_type', 20)->nullable(); // image | pdf (opsional)

            $table->longText('content')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['section', 'slug']);
            $table->index(['section', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
