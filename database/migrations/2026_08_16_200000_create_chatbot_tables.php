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
        Schema::create('chatbot_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('chatbot_nodes')->onDelete('cascade');
            $table->foreignId('target_node_id')->nullable()->constrained('chatbot_nodes')->onDelete('set null');
            $table->string('title');
            $table->text('bot_response')->nullable();
            $table->string('action_type')->default('node'); // node, jump, module, root, back, info
            $table->string('module_key')->nullable();
            $table->string('action_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chatbot_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token')->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('chatbot_sessions')->onDelete('cascade');
            $table->foreignId('node_id')->nullable()->constrained('chatbot_nodes')->onDelete('set null');
            $table->string('user_action');
            $table->text('bot_response_summary')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
        Schema::dropIfExists('chatbot_sessions');
        Schema::dropIfExists('chatbot_nodes');
    }
};
