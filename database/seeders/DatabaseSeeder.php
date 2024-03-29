<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            CategoriesSeeder::class,
            ProductSeeder::class,
            CartItemsSeeder::class,
            OrderSeeder::class,

        ]);
    }
}
