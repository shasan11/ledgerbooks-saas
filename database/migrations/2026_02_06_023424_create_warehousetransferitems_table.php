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

        Schema::create('warehousetransferitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('qty', 18, 6);
            $table->string('note', 255)->nullable();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('product_variant_id')->constrained();
            $table->foreignUuid('warehouse_transfer_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehousetransferitems');
    }
};
