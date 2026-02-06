<?php

namespace Database\Factories;

use App\Models\CustomerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => fake()->word(),
            'customer_profile_id' => CustomerProfile::factory(),
            'label' => fake()->regexify('[A-Za-z0-9]{80}'),
            'full_name' => fake()->regexify('[A-Za-z0-9]{180}'),
            'phone' => fake()->phoneNumber(),
            'address1' => fake()->streetAddress(),
            'address2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->regexify('[A-Za-z0-9]{80}'),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'is_default' => fake()->boolean(),
        ];
    }
}
