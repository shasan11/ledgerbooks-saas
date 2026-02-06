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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('production_no', 50)->nullable()->index();
            $table->date('production_date')->index();
            $table->foreignId('warehouse_id');
            $table->enum('status', ["draft","in_progress","completed","void"])->default('draft')->index();
            $table->string('finished_good_variant_id');
            $table->decimal('planned_qty', 18, 6)->default(0);
            $table->decimal('produced_qty', 18, 6)->default(0);
            $table->boolean('approved')->default(false);
            $table->dateTime('approved_at')->nullable();
            $table->string('approved_by_id')->nullable();
            $table->string('voided_reason', 255)->nullable();
            $table->dateTime('voided_at')->nullable();
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('total', 18, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('product_variant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
