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
        Schema::create('journal_voucher_items', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('journal_voucher_id');
            $table->string('account_id');
            $table->decimal('dr_amount', 18, 2)->default(0);
            $table->decimal('cr_amount', 18, 2)->default(0);
            $table->string('line_note', 255)->nullable();
            $table->foreignId('c_o_a_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_items');
    }
};
