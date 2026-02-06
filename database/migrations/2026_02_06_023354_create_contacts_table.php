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

        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('type', 20);
            $table->string('name', 200);
            $table->string('code', 50)->nullable();
            $table->string('pan', 50)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address')->nullable();
            $table->boolean('accept_purchase');
            $table->integer('credit_terms_days');
            $table->decimal('credit_limit', 18, 6);
            $table->string('notes')->nullable();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('payable_account_id')->nullable()->constrained();
            $table->foreignUuid('receivable_account_id')->nullable()->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->foreignUuid('group_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
