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
        Schema::create('supplier_payment_lines', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('supplier_payment_id');
            $table->foreignId('purchase_bill_id');
            $table->decimal('allocated_amount', 18, 2)->default(0);
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payment_lines');
    }
};
