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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->enum('type', ["customer","supplier","lead"])->index();
            $table->string('name', 200);
            $table->string('code', 50)->nullable()->index();
            $table->string('pan', 50)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('group_id')->nullable();
            $table->boolean('accept_purchase')->default(false);
            $table->integer('credit_terms_days')->default(0);
            $table->decimal('credit_limit', 18, 2)->default(0);
            $table->string('receivable_account_id')->nullable();
            $table->string('payable_account_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('contact_group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
