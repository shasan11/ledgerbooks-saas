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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('order_id');
            $table->foreignId('product_variant_id');
            $table->string('product_name', 200)->nullable();
            $table->decimal('qty', 18, 6)->default(1);
            $table->decimal('unit_price', 18, 6)->default(0);
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
        Schema::dropIfExists('order_items');
    }
};
