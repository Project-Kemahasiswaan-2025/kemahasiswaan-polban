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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('competitions')
                ->nullOnDelete();

            $table->string('name', 180);
            $table->string('slug', 200)->unique();

            $table->boolean('is_group')->default(false); // kategori vs item
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->string('cover_image')->nullable();

            // eksternal link (mayoritas)
            $table->string('url', 255)->nullable();
            $table->string('url_target', 20)->default('_blank');

            // opsional, kalau ada kompetisi yang mau dijelasin
            $table->longText('content')->nullable();

            $table->timestamps();
            $table->index(['parent_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
