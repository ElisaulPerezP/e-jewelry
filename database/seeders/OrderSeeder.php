<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::factory()->create([
            'name' => 'Buyer',
            'email' => 'buyer@jewelry.com',
        ]);

        $buyer->assignRole('user');

        CartItem::factory()
            ->count(20)
            ->for($buyer)
            ->create([
                'state' => array_rand(['selected' => 0, 'in_cart' => 1]),
            ]);

        $orders = Order::factory()
            ->count(10)
            ->for($buyer)
            ->has(CartItem::factory()->count(10)->for($buyer))
            ->create();

        foreach ($orders as $order) {
            $order->setTotal();
        }
    }
}
