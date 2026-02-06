<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::rename('branches', 'branches_old');

            Schema::create('branches', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->string('email', 150)->nullable();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->string('country', 80)->nullable();
                $table->string('city', 80)->nullable();
                $table->string('timezone', 64)->nullable();
                $table->foreignId('currency_id')->nullable();
                $table->boolean('is_head_office')->default(false);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });

            DB::table('branches')->insertUsing(
                [
                    'id',
                    'code',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'country',
                    'city',
                    'timezone',
                    'currency_id',
                    'is_head_office',
                    'active',
                    'created_at',
                    'updated_at',
                ],
                DB::table('branches_old')->select(
                    'id',
                    'code',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'country',
                    'city',
                    'timezone',
                    'currency_id',
                    'is_head_office',
                    'active',
                    'created_at',
                    'updated_at'
                )
            );

            Schema::drop('branches_old');

            return;
        }

        Schema::table('branches', function (Blueprint $table) {
            $table->primary('id');
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::rename('branches', 'branches_old');

            Schema::create('branches', function (Blueprint $table) {
                $table->uuid('id');
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->string('email', 150)->nullable();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->string('country', 80)->nullable();
                $table->string('city', 80)->nullable();
                $table->string('timezone', 64)->nullable();
                $table->foreignId('currency_id')->nullable();
                $table->boolean('is_head_office')->default(false);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });

            DB::table('branches')->insertUsing(
                [
                    'id',
                    'code',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'country',
                    'city',
                    'timezone',
                    'currency_id',
                    'is_head_office',
                    'active',
                    'created_at',
                    'updated_at',
                ],
                DB::table('branches_old')->select(
                    'id',
                    'code',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'country',
                    'city',
                    'timezone',
                    'currency_id',
                    'is_head_office',
                    'active',
                    'created_at',
                    'updated_at'
                )
            );

            Schema::drop('branches_old');

            return;
        }

        Schema::table('branches', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });
    }
};
