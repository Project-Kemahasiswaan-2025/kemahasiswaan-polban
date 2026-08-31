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
        Schema::table('chatbot_nodes', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('title');
            $table->string('action_label')->nullable()->after('action_type');
            $table->string('action_icon')->nullable()->after('action_label');
            $table->string('action_icon_position', 10)->default('left')->after('action_icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_nodes', function (Blueprint $table) {
            $table->dropColumn(['icon', 'action_label', 'action_icon', 'action_icon_position']);
        });
    }
};
