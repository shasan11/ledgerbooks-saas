<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\ContactGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'type' => fake()->randomElement(["customer","supplier","lead"]),
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'pan' => fake()->regexify('[A-Za-z0-9]{50}'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'address' => fake()->text(),
            'group_id' => fake()->numberBetween(1, 1000),
            'accept_purchase' => fake()->boolean(),
            'credit_terms_days' => fake()->numberBetween(-10000, 10000),
            'credit_limit' => fake()->randomFloat(2, 0, 9999999999999999.99),
            'receivable_account_id' => fake()->numberBetween(1, 1000),
            'payable_account_id' => fake()->numberBetween(1, 1000),
            'notes' => fake()->text(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
            'contact_group_id' => ContactGroup::factory(),
        ];
    }
}
