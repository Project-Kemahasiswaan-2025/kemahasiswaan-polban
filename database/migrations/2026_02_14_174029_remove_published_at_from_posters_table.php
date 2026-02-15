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
        Schema::table('posters', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id', 'published_at']);
            $table->dropColumn('published_at');

            $table->foreignId('category_id')
                ->change()
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posters', function (Blueprint $table) {
            $table->date('published_at')->nullable();
            $table->index(['category_id', 'published_at']);
        });
    }
};
