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

        Schema::create('payslips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->boolean('approved');
            $table->timestamp('approved_at')->nullable();
            $table->string('voided_reason')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->decimal('exchange_rate', 18, 6);
            $table->string('status', 10);
            $table->decimal('gross_total', 18, 6);
            $table->decimal('deduction_total', 18, 6);
            $table->decimal('net_total', 18, 6);
            $table->decimal('total', 18, 6);
            $table->string('note')->nullable();
            $table->foreignUuid('approved_by_id')->nullable()->constrained();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('employee_id')->constrained();
            $table->foreignUuid('payroll_period_id')->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
