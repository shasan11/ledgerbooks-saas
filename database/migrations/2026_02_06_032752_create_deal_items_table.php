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
        Schema::create('deal_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->foreignId('deal_id');
            $table->foreignId('product_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('qty', 18, 6)->default(1);
            $table->decimal('rate', 18, 6)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->foreignId('tax_rate_id')->nullable();
            $table->decimal('line_total', 18, 2)->default(0);
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
        Schema::dropIfExists('deal_items');
    }
};
