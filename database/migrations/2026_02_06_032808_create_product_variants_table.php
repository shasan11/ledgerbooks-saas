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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->foreignId('product_id');
            $table->string('sku', 120)->nullable()->index();
            $table->string('name', 200)->nullable();
            $table->string('option_summary', 255)->nullable();
            $table->decimal('selling_price', 18, 6)->default(0);
            $table->decimal('purchase_price', 18, 6)->default(0);
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
