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

        Schema::create('dealitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('description', 255)->nullable();
            $table->decimal('qty', 18, 6);
            $table->decimal('rate', 18, 6);
            $table->decimal('discount_amount', 18, 6);
            $table->decimal('line_total', 18, 6);
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('deal_id')->constrained();
            $table->foreignUuid('product_id')->nullable()->constrained();
            $table->foreignUuid('tax_rate_id')->nullable()->constrained();
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
        Schema::dropIfExists('dealitems');
    }
};
