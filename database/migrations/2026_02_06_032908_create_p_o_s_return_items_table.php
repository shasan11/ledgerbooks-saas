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
        Schema::create('p_o_s_return_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('pos_return_id');
            $table->string('pos_order_item_id')->nullable();
            $table->foreignId('product_variant_id');
            $table->decimal('qty', 18, 6)->default(1);
            $table->decimal('unit_price', 18, 6)->default(0);
            $table->foreignId('tax_rate_id')->nullable();
            $table->decimal('line_total', 18, 2)->default(0);
            $table->foreignId('p_o_s_return_id');
            $table->foreignId('p_o_s_order_item_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_return_items');
    }
};
