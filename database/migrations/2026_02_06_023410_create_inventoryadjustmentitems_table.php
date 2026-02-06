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

        Schema::create('inventoryadjustmentitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('qty_change', 18, 6);
            $table->decimal('unit_cost', 18, 6);
            $table->string('note', 255)->nullable();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('inventory_adjustment_id')->constrained();
            $table->foreignUuid('product_variant_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoryadjustmentitems');
    }
};
