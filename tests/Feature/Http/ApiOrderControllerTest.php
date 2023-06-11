<?php

namespace Tests\Feature\Http;

use App\Models\ItemCart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiOrderControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testItCanRetrieveOrdersIndex(): void
    {
        $admin = User::factory()->create();
        $product = Product::factory()->create();
        $itemCart = ItemCart::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'selected',
        ]);
        $permission = Permission::findOrCreate('api.order.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        Order::factory()->create([
            'user_id' => $admin->id,
            'reference' => 1,
            'total' => $product->price * $itemCart->amount,
            'currency' => 'COP',
            'state' => 'pending',
            'return_url' => 'http:/test',
            'process_url' => null,
        ])->save();

        $response = $this->actingAs($admin, 'api')->getJson(route('api.order.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'reference',
                    'total',
                    'currency',
                    'order_state',
                    'return_url',
                    'process_url',
                ],
            ],
        ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertTrue(Cache::has('orders'));
    }
    public function testItCanCreateOrder(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        ItemCart::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state'=>'selected']);
        ItemCart::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state'=>'selected']);
        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));

        $orderCreated = Order::findOrFail($response->json()['data']['id']);
        $response->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals($user->id, $orderCreated->user_id);
    }

    public function testItCanUpdateOrderState(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        ItemCart::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state' => 'selected']);
        ItemCart::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state' => 'selected']);
        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));

        $orderCreated = Order::findOrFail($response->json()['data']['id']);
        $response->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals($user->id, $orderCreated->user_id);
        $this->assertEquals('pending', $orderCreated->state);
    }
}
