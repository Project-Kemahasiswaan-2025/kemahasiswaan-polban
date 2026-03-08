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
        Schema::create('student_organizations', function (Blueprint $table) {
            $table->id();

            // Parent = kategori utama
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('student_organizations')
                ->nullOnDelete();

            $table->boolean('is_group')->default(false);
            $table->string('name', 180);
            $table->string('slug', 200);

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // disiapkan tapi belum dipakai input (tetap ada)
            $table->text('excerpt')->nullable();

            $table->longText('content')->nullable();

            $table->string('cta_label', 60)->nullable();
            $table->string('cta_url', 255)->nullable();

            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_organizations');
    }
};
