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
        $tables = [
            'banners',
            'videos',
            'student_organizations',
            'competitions',
            'running_texts',
            'pages'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'language_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Try to drop foreign key if it exists
                    try {
                        $table->dropForeign([$tableName . '_language_id_foreign']);
                    } catch (\Exception $e) {
                        // Ignore if foreign key doesn't exist
                    }

                    // Try to drop index if it exists
                    try {
                        $table->dropIndex([$tableName . '_language_id_index']);
                    } catch (\Exception $e) {
                        // Ignore if index doesn't exist
                    }

                    $table->dropColumn('language_id');
                });
            }
        }

        Schema::dropIfExists('languages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this as we are dropping a table and columns
    }
};
