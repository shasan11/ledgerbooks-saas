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
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('expense_no', 50)->nullable()->index();
            $table->date('expense_date')->index();
            $table->string('supplier_id')->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->enum('status', ["draft","posted","void"])->default('draft')->index();
            $table->text('description')->nullable();
            $table->decimal('grand_total', 18, 2)->default(0);
            $table->string('expense_account_id')->nullable();
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
            $table->foreignId('contact_id');
            $table->foreignId('c_o_a_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
