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
        Schema::create('p_o_s_receipts', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('pos_order_id');
            $table->string('receipt_no', 60)->nullable()->index();
            $table->dateTime('printed_at')->nullable();
            $table->longText('payload')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('p_o_s_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_receipts');
    }
};
