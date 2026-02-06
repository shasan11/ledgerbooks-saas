<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
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
            'code' => fake()->regexify('[A-Za-z0-9]{50}'),
            'title' => fake()->sentence(4),
            'stage' => fake()->regexify('[A-Za-z0-9]{20}'),
            'expected_close' => fake()->word(),
            'probability' => fake()->numberBetween(-10000, 10000),
            'expected_value' => fake()->randomFloat(6, 0, 999999999999.999999),
            'source' => fake()->regexify('[A-Za-z0-9]{80}'),
            'description' => fake()->text(),
            'branch_id' => Branch::factory(),
            'contact_id' => Contact::factory(),
            'currency_id' => Currency::factory(),
            'owner_id' => Owner::factory(),
            'user_add_id' => User::factory(),
        ];
    }
}
