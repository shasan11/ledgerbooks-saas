<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\CoaAccount;
use App\Models\Currency;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankaccountFactory extends Factory
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
            'type' => fake()->regexify('[A-Za-z0-9]{10}'),
            'bank_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'display_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'account_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'account_number' => fake()->regexify('[A-Za-z0-9]{80}'),
            'account_type' => fake()->regexify('[A-Za-z0-9]{10}'),
            'description' => fake()->text(),
            'branch_id' => Branch::factory(),
            'currency_id' => Currency::factory(),
            'user_add_id' => UserAdd::factory(),
            'coa_account_id' => CoaAccount::factory(),
        ];
    }
}
