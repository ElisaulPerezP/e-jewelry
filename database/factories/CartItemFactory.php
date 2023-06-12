<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => random_int(1, Product::count()),
            'amount' => $this->faker->randomNumber(2),
            'state' => 'in_order',
        ];
    }
}
