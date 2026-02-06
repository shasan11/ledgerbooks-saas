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

        Schema::create('poscashmovements', function (Blueprint $table) {
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
            $table->string('type', 5);
            $table->decimal('amount', 18, 6);
            $table->string('reason', 255)->nullable();
            $table->decimal('total', 18, 6);
            $table->string('note')->nullable();
            $table->foreignUuid('approved_by_id')->nullable()->constrained();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('user_add_id')->nullable()->constrained();
            $table->foreignUuid('shift_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poscashmovements');
    }
};
