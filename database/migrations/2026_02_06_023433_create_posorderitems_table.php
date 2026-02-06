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

        Schema::create('posorderitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_name', 200)->nullable();
            $table->decimal('qty', 18, 6);
            $table->decimal('unit_price', 18, 6);
            $table->decimal('discount_amount', 18, 6);
            $table->decimal('line_total', 18, 6);
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('pos_order_id')->constrained();
            $table->foreignUuid('product_variant_id')->constrained();
            $table->foreignUuid('tax_rate_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posorderitems');
    }
};
