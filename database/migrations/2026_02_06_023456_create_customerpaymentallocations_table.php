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

        Schema::create('customerpaymentallocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('allocated_amount', 18, 6);
            $table->string('note', 255)->nullable();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('customer_payment_id')->constrained();
            $table->foreignUuid('invoice_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerpaymentallocations');
    }
};
