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
        Schema::create('deal_activities', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->enum('type', ["call","meeting","task","email","note"])->index();
            $table->string('subject', 200);
            $table->foreignId('contact_id')->nullable();
            $table->foreignId('deal_id')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('status', ["pending","done","cancelled"])->default('pending')->index();
            $table->string('assigned_to_id')->nullable();
            $table->text('description')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_activities');
    }
};
