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
        Schema::create('cash_transfer_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('cash_transfer_id');
            $table->string('to_account_id');
            $table->decimal('amount', 18, 2)->default(0);
            $table->string('note', 255)->nullable();
            $table->foreignId('bank_account_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transfer_items');
    }
};
