<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->numberBetween(1, 1000),
            'type' => fake()->randomElement(["call","meeting","task","email","note"]),
            'subject' => fake()->regexify('[A-Za-z0-9]{200}'),
            'contact_id' => Contact::factory(),
            'deal_id' => fake()->numberBetween(1, 1000),
            'due_at' => fake()->dateTime(),
            'completed_at' => fake()->dateTime(),
            'status' => fake()->randomElement(["pending","done","cancelled"]),
            'assigned_to_id' => fake()->numberBetween(1, 1000),
            'description' => fake()->text(),
            'user_add_id' => fake()->numberBetween(1, 1000),
            'active' => fake()->boolean(),
            'is_system_generated' => fake()->boolean(),
        ];
    }
}
