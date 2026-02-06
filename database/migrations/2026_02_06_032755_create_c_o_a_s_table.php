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
        Schema::create('c_o_a_s', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->string('name', 200);
            $table->string('code', 60)->index();
            $table->text('description')->nullable();
            $table->string('parent_id')->nullable();
            $table->foreignId('account_type_id');
            $table->boolean('is_group')->default(false);
            $table->boolean('is_system')->default(false);
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
        Schema::dropIfExists('c_o_a_s');
    }
};
