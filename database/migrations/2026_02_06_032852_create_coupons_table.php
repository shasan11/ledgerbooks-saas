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
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('code', 40)->unique();
            $table->string('name', 120);
            $table->enum('discount_type', ["percent","fixed"])->default('percent');
            $table->decimal('discount_value', 18, 6)->default(0);
            $table->decimal('min_order_amount', 18, 2)->default(0);
            $table->integer('max_uses')->nullable();
            $table->integer('max_uses_per_customer')->nullable();
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
