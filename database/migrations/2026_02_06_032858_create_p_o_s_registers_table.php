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
        Schema::create('p_o_s_registers', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('code', 40)->nullable()->index();
            $table->string('name', 150);
            $table->foreignId('warehouse_id')->nullable();
            $table->string('cash_account_id')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('bank_account_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_registers');
    }
};
