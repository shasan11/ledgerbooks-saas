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

        Schema::create('deals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('created');
            $table->timestamp('updated');
            $table->boolean('active');
            $table->boolean('is_system_generated');
            $table->string('code', 50)->nullable();
            $table->string('title', 200);
            $table->string('stage', 20);
            $table->string('expected_close')->nullable();
            $table->integer('probability');
            $table->decimal('expected_value', 18, 6);
            $table->string('source', 80)->nullable();
            $table->string('description')->nullable();
            $table->foreignUuid('branch_id')->nullable()->constrained();
            $table->foreignUuid('contact_id')->constrained();
            $table->foreignUuid('currency_id')->nullable()->constrained();
            $table->foreignUuid('owner_id')->nullable()->constrained();
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
        Schema::dropIfExists('deals');
    }
};
