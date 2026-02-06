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

        Schema::create('bankaccounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('type', 10);
            $table->string('bank_name', 150)->nullable();
            $table->string('display_name', 150);
            $table->string('code', 50)->nullable();
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 80)->nullable();
            $table->string('account_type', 10)->nullable();
            $table->string('description')->nullable();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('currency_id')->nullable()->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->foreignUuid('coa_account_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bankaccounts');
    }
};
