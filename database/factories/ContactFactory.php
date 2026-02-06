<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Group;
use App\Models\PayableAccount;
use App\Models\ReceivableAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created' => fake()->dateTime(),
            'updated' => fake()->dateTime(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'type' => fake()->regexify('[A-Za-z0-9]{20}'),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'pan' => fake()->regexify('[A-Za-z0-9]{50}'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'address' => fake()->word(),
            'accept_purchase' => fake()->boolean(),
            'credit_terms_days' => fake()->numberBetween(-10000, 10000),
            'credit_limit' => fake()->randomFloat(6, 0, 999999999999.999999),
            'notes' => fake()->word(),
            'branch_id' => Branch::factory(),
            'payable_account_id' => PayableAccount::factory(),
            'receivable_account_id' => ReceivableAccount::factory(),
            'user_add_id' => User::factory(),
            'group_id' => Group::factory(),
        ];
    }
}
