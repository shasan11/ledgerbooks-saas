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
        Schema::create('deals', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('branch_id');
            $table->string('code', 50)->nullable()->index();
            $table->string('title', 200);
            $table->foreignId('contact_id');
            $table->enum('stage', ["lead","qualified","proposal","won","lost"])->default('lead')->index();
            $table->date('expected_close')->nullable();
            $table->integer('probability')->default(0);
            $table->foreignId('currency_id')->nullable();
            $table->decimal('expected_value', 18, 2)->default(0);
            $table->string('source', 80)->nullable();
            $table->string('owner_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('deals');
    }
};
