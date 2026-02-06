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

        Schema::create('customusers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password', 128);
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_superuser');
            $table->string('username', 150)->unique();
            $table->string('first_name', 150);
            $table->string('last_name', 150);
            $table->boolean('is_staff');
            $table->boolean('is_active');
            $table->timestamp('date_joined');
            $table->string('profile', 100)->nullable();
            $table->string('user_type', 50)->nullable();
            $table->string('email', 254)->unique();
            $table->char('branch_id', 32)->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customusers');
    }
};
