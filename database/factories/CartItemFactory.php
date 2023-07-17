<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => random_int(1, Product::count()),
            'user_id' => random_int(1, User::count()),
            'amount' => $this->faker->randomNumber(2),
            'state' => $this->faker->randomElement(['in_cart', 'selected', 'in_order', 'collected', 'paid', 'dispatched']),
        ];
    }
}
