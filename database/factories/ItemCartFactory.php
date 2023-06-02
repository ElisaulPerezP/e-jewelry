<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemCartFactory extends Factory
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
            'product_id'=> random_int(1, Product::count()),
            'amount'=> $this->faker->randomNumber(2),
            'item_state' => $this->faker->randomElement(['in_cart', 'saved']),
        ];
    }
}
