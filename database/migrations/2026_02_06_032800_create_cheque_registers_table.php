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
        Schema::create('cheque_registers', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->string('cheque_no', 80)->nullable()->index();
            $table->string('coa_account_id')->nullable();
            $table->foreignId('bank_account_id')->nullable();
            $table->foreignId('contact_id')->nullable();
            $table->date('cheque_date')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('amount', 18, 2)->default(0);
            $table->enum('status', ["issued","received","cleared","bounced","cancelled"])->default('received')->index();
            $table->string('memo', 255)->nullable();
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
            $table->foreignId('c_o_a_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_registers');
    }
};
