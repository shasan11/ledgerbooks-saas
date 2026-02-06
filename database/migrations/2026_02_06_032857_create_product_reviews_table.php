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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->foreignId('store_id');
            $table->foreignId('product_id');
            $table->foreignId('customer_profile_id')->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->string('title', 120)->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('product_reviews');
    }
};
