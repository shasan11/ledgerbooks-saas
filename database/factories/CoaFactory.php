<?php

namespace Database\Factories;

use App\Models\AccountType;
use App\Models\Branch;
use App\Models\Parent;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoaFactory extends Factory
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
            'name' => fake()->name(),
            'code' => fake()->regexify('[A-Za-z0-9]{60}'),
            'description' => fake()->text(),
            'is_group' => fake()->boolean(),
            'is_system' => fake()->boolean(),
            'account_type_id' => AccountType::factory(),
            'branch_id' => Branch::factory(),
            'parent_id' => Parent::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
