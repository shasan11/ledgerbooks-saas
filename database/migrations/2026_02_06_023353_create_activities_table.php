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
        Schema::disableForeignKeyConstraints();

        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('type', 20);
            $table->string('subject', 200);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 20);
            $table->string('description')->nullable();
            $table->foreignUuid('assigned_to_id')->nullable()->constrained();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->foreignUuid('contact_id')->nullable()->constrained();
            $table->foreignUuid('deal_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
