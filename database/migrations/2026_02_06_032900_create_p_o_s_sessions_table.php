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
        Schema::create('p_o_s_sessions', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('branch_id');
            $table->string('shift_id');
            $table->string('device_id', 120)->nullable()->index();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->string('user_add_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_system_generated')->default(false);
            $table->foreignId('p_o_s_shift_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_s_sessions');
    }
};
