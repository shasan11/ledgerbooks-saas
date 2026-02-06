<?php

namespace Database\Factories;

use App\Models\AssignedTo;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\UserAdd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
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
            'subject' => fake()->regexify('[A-Za-z0-9]{200}'),
            'due_at' => fake()->dateTime(),
            'completed_at' => fake()->dateTime(),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'description' => fake()->text(),
            'assigned_to_id' => AssignedTo::factory(),
            'branch_id' => Branch::factory(),
            'user_add_id' => UserAdd::factory(),
            'contact_id' => Contact::factory(),
            'deal_id' => Deal::factory(),
        ];
    }
}
