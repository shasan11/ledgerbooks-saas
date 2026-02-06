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

        Schema::create('productvariants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('sku', 120)->nullable();
            $table->string('name', 200)->nullable();
            $table->string('option_summary', 255)->nullable();
            $table->decimal('selling_price', 18, 6);
            $table->decimal('purchase_price', 18, 6);
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('product_id')->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productvariants');
    }
};
