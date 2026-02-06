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
        Schema::create('production_inputs', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('production_order_id');
            $table->string('raw_material_variant_id');
            $table->decimal('qty_required', 18, 6)->default(0);
            $table->decimal('qty_consumed', 18, 6)->default(0);
            $table->foreignId('product_variant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_inputs');
    }
};
