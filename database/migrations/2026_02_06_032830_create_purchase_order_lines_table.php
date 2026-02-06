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
        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('purchase_order_id');
            $table->foreignId('product_id')->nullable();
            $table->string('product_name', 200)->nullable();
            $table->decimal('qty', 18, 6)->default(1);
            $table->decimal('rate', 18, 6)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->foreignId('tax_rate_id')->nullable();
            $table->decimal('line_total', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_lines');
    }
};
