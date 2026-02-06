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
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->foreignId('order_payment_id');
            $table->string('gateway', 80);
            $table->string('transaction_id', 150)->index();
            $table->longText('request_payload')->nullable();
            $table->longText('response_payload')->nullable();
            $table->string('status', 50)->nullable()->index();
            $table->dateTime('processed_at')->nullable();
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
        Schema::dropIfExists('payment_gateway_transactions');
    }
};
