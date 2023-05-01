<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomNumber(6),
            'productSubcategory' => fake()->numberBetween(1, 30),
            'stock' => fake()->randomNumber(2),
            'score' => fake()->randomNumber(1),
            'status' => fake()->boolean,
            'barCode' => fake()->randomNumber(6),
        ];
    }
}
