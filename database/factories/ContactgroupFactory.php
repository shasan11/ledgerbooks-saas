<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Parent;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactgroupFactory extends Factory
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
            'description' => fake()->text(),
            'branch_id' => Branch::factory(),
            'parent_id' => Parent::factory(),
            'user_add_id' => UserAdd::factory(),
        ];
    }
}
