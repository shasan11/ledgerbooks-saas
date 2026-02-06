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

        Schema::create('journalvoucheritems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('dr_amount', 18, 6);
            $table->decimal('cr_amount', 18, 6);
            $table->string('line_note', 255)->nullable();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->foreignUuid('account_id')->constrained();
            $table->foreignUuid('journal_voucher_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journalvoucheritems');
    }
};
