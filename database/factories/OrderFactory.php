<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => random_int(1, User::count()),
            'reference' => uuid_create(),
            'total' => fake()->randomNumber(6),
            'currency' => $this->faker->randomElement(['USD', 'COP']),
            'state' => $this->faker->randomElement(['processing', 'reject', 'approved']),
            'return_url' => 'http:/test',
            'process_url' => null,
             ];
    }
}
