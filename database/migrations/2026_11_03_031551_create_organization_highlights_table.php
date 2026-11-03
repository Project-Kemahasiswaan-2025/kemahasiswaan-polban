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
        Schema::create('organization_highlights', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_organization_id')
                ->constrained('student_organizations')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['student_organization_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_highlights');
    }
};
