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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->enum('type', ["goods","service"])->index();
            $table->string('name', 200);
            $table->string('code', 80)->nullable()->index();
            $table->string('category_id')->nullable();
            $table->foreignId('tax_class_id')->nullable();
            $table->string('primary_unit_id')->nullable();
            $table->string('hs_code', 40)->nullable();
            $table->boolean('ecommerce_enabled')->default(false);
            $table->boolean('pos_enabled')->default(false);
            $table->text('description')->nullable();
            $table->decimal('selling_price', 18, 6)->default(0);
            $table->decimal('purchase_price', 18, 6)->default(0);
            $table->string('sales_account_id')->nullable();
            $table->string('purchase_account_id')->nullable();
            $table->string('purchase_return_account_id')->nullable();
            $table->enum('valuation_method', ["fifo","weighted_average"])->default('fifo');
            $table->boolean('track_inventory')->default(true);
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('product_category_id');
            $table->foreignId('unit_of_measurement_id');
            $table->foreignId('c_o_a_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
