<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => uuid_create(),
            'currency' => $this->faker->randomElement(['USD', 'COP']),
            'state' => $this->faker->randomElement(['pending', 'rejected', 'approved']),
            'return_url' => 'http:/test',
        ];
    }
}
