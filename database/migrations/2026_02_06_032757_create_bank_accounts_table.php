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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->enum('type', ["bank","cash"])->index();
            $table->string('bank_name', 150)->nullable();
            $table->string('display_name', 150);
            $table->string('code', 50)->nullable()->index();
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 80)->nullable();
            $table->enum('account_type', ["saving","current"])->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->string('coa_account_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('bank_accounts');
    }
};
