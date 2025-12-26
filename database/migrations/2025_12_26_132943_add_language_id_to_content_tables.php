<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['banners', 'videos', 'student_organizations'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('language_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('languages')
                    ->nullOnDelete();
                
                $table->index('language_id');
            });
        }

        // Set default language_id to Indonesian (id = 1) for existing records
        foreach ($tables as $table) {
            DB::table($table)->whereNull('language_id')->update(['language_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['banners', 'videos', 'student_organizations'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['language_id']);
                $table->dropIndex([$table->getTable() . '_language_id_index']);
                $table->dropColumn('language_id');
            });
        }
    }
};
