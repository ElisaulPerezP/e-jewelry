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
            'payment_reference' => uuid_create(),
            'description' => fake()->sentence(5),
            'total' => fake()->randomNumber(6),
            'currency' => $this->faker->randomElement(['USD', 'COP']),
            'order_state' => $this->faker->randomElement(['processing', 'reject', 'approved']),
            'expiration' => date('c'),
            'return_url' => 'http:/test',
            'process_url' => null,
             ];
    }
}
