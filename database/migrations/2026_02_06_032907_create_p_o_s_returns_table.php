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
        Schema::create('p_o_s_returns', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('return_no', 60)->nullable()->index();
            $table->dateTime('return_date')->index();
            $table->string('pos_order_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('reason', 255)->nullable();
            $table->enum('status', ["draft","posted","void"])->default('draft')->index();
            $table->decimal('total', 18, 2)->default(0);
            $table->boolean('approved')->default(false);
            $table->dateTime('approved_at')->nullable();
            $table->string('approved_by_id')->nullable();
            $table->string('voided_reason', 255)->nullable();
            $table->dateTime('voided_at')->nullable();
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->text('note')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('p_o_s_order_id');
            $table->foreignId('contact_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_returns');
    }
};
