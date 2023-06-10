<?php

namespace Database\Seeders;

use App\Models\ItemCart;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory()
            ->count(1000)
            ->create();

        $orderCount = Order::count();

        for ($i = 1; $i <= $orderCount; $i++) {
            $order = Order::find($i);
            $itemCarts = ItemCart::inRandomOrder()->limit(3)->get();
        }
    }
}
