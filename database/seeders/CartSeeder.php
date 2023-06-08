<?php

namespace Database\Seeders;

use App\Models\ItemCart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        ItemCart::factory()
            ->count(1000)
            ->create();
    }
}
