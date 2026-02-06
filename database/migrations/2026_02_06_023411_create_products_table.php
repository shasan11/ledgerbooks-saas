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

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('type', 10);
            $table->string('name', 200);
            $table->string('code', 80)->nullable();
            $table->string('hs_code', 40)->nullable();
            $table->boolean('ecommerce_enabled');
            $table->boolean('pos_enabled');
            $table->string('description')->nullable();
            $table->decimal('selling_price', 18, 6);
            $table->decimal('purchase_price', 18, 6);
            $table->string('valuation_method', 20);
            $table->boolean('track_inventory');
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('purchase_account_id')->nullable()->constrained();
            $table->foreignUuid('purchase_return_account_id')->nullable()->constrained();
            $table->foreignUuid('sales_account_id')->nullable()->constrained();
            $table->foreignUuid('tax_class_id')->nullable()->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->foreignUuid('category_id')->nullable()->constrained();
            $table->foreignUuid('primary_unit_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
