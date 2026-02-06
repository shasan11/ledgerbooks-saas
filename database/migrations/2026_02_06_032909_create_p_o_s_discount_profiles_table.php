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
        Schema::create('p_o_s_discount_profiles', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('name', 120);
            $table->enum('discount_type', ["percent","fixed"])->default('percent');
            $table->decimal('value', 18, 6)->default(0);
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_discount_profiles');
    }
};
