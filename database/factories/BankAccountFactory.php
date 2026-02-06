<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Branch;
use App\Models\COA;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'type' => fake()->randomElement(["bank","cash"]),
            'bank_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'display_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'account_name' => fake()->regexify('[A-Za-z0-9]{150}'),
            'account_number' => fake()->regexify('[A-Za-z0-9]{80}'),
            'account_type' => fake()->randomElement(["saving","current"]),
            'currency_id' => ::factory(),
            'coa_account_id' => fake()->word(),
            'description' => fake()->text(),
            'user_add_id' => fake()->word(),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'c_o_a_id' => COA::factory(),
        ];
    }
}
