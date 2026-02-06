<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomuserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'password' => fake()->password(),
            'last_login' => fake()->dateTime(),
            'is_superuser' => fake()->boolean(),
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'is_staff' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'date_joined' => fake()->dateTime(),
            'profile' => fake()->regexify('[A-Za-z0-9]{100}'),
            'user_type' => fake()->regexify('[A-Za-z0-9]{50}'),
            'email' => fake()->safeEmail(),
            'branch_id' => fake()->randomLetter(),
        ];
    }
}
