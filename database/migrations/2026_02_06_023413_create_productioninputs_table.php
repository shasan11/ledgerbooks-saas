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

        Schema::create('productioninputs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('qty_required', 18, 6);
            $table->decimal('qty_consumed', 18, 6);
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('production_order_id')->constrained();
            $table->foreignUuid('raw_material_variant_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productioninputs');
    }
};
