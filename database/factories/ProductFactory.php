<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageFiles = Storage::allFiles('public/products');
        $imageCollection = collect($imageFiles);
        $randomImageName = $imageCollection->random();
        $randomImageName = basename($randomImageName);

        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(5),
            'price' => fake()->randomNumber(6),
            'stock' => 1000,
            'score' => fake()->randomNumber(1),
            'status' => fake()->boolean,
            'barCode' => fake()->randomNumber(6),
            'image' => 'products/' . $randomImageName,
        ];
    }
}
