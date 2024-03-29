<?php

namespace Tests\Feature;

use App\Models\CartItem;
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
        CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'selected',
        ]);
        $permission = Permission::findOrCreate('api.order.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $this->actingAs($admin, 'api')->postJson(route('api.order.store'));

        $response = $this->actingAs($admin, 'api')->getJson(route('api.order.index', ['searching' => '', 'current_page' => 1, 'per_page' => 1, 'flag' => 0]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'reference',
                    'total',
                    'currency',
                    'state',
                    'return_url',
                    'process_url',
                ],
            ],
        ]);
        $this->assertDatabaseCount('orders', 1);
        $this->assertTrue(Cache::has('orders'));
    }

    public function testItCanRetrieveAOrder(): void
    {
        $admin = User::factory()->create();
        $product = Product::factory()->create();
        CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'selected',
        ]);
        $permission = Permission::findOrCreate('api.order.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $order = $this->actingAs($admin, 'api')->postJson(route('api.order.store'));
        $response = $this->actingAs($admin, 'api')->getJson(route('api.order.show', $order['data']['id']));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                    'id',
                    'reference',
                    'total',
                    'currency',
                    'state',
                    'return_url',
                    'process_url',
                ],
        ]);
        $this->assertDatabaseCount('orders', 1);
        $this->assertFalse(Cache::has('orders'));
    }
    public function testItCanCreateOrder(): void
    {
        $user = User::factory()->create();
        $permission = Permission::findOrCreate('api.order.store', 'api');
        $role = Role::findOrCreate('user', 'api')->givePermissionTo($permission);
        $user->assignRole($role);
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state'=>'selected']);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state'=>'selected']);
        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));

        $orderCreated = Order::findOrFail($response->json()['data']['id']);
        $response->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals($user->id, $orderCreated->user_id);
    }

    public function testItCanUpdateOrderState(): void
    {
        $user = User::factory()->create();
        $permission = Permission::findOrCreate('api.order.store', 'api');
        $role = Role::findOrCreate('user', 'api')->givePermissionTo($permission);
        $user->assignRole($role);
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state' => 'selected']);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state' => 'selected']);
        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));

        $orderCreated = Order::findOrFail($response->json()['data']['id']);
        $response->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals($user->id, $orderCreated->user_id);
        $this->assertEquals('pending', $orderCreated->state);
    }

    public function testItCanRetryAnOrder(): void
    {
        $user = User::factory()->create();
        $permission1 = Permission::findOrCreate('api.order.store', 'api');
        $permission2 = Permission::findOrCreate('api.order.retry', 'api');
        $role = Role::findOrCreate('user', 'api')->givePermissionTo($permission1, $permission2);
        $user->assignRole($role);
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state' => 'selected']);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state' => 'selected']);

        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));
        $order = Order::findOrFail($response->json()['data']['id']);
        $order->rejected();

        $response = $this->actingAs($user, 'api')->postJson(route('api.order.retry', $order));
        $order = Order::findOrFail($response->json()['data']['id']);

        $response->assertCreated();
        $this->assertDatabaseCount('orders', 2);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals('pending', $order->state);
    }

    public function testItCanRetriveOrderItems(): void
    {
        $user = User::factory()->create();
        $permission = Permission::findOrCreate('api.order.store', 'api');
        $role = Role::findOrCreate('user', 'api')->givePermissionTo($permission);
        $user->assignRole($role);
        $product1 = Product::factory()->create(['price' => 10000]);
        $product2 = Product::factory()->create(['price' => 20000]);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 1, 'product_id' => $product1->id, 'state'=>'selected']);
        CartItem::factory()->create(['user_id' => $user->id, 'amount' => 2, 'product_id' => $product2->id, 'state'=>'selected']);
        $response = $this->actingAs($user, 'api')->postJson(route('api.order.store'));
        $orderCreated = Order::findOrFail($response->json()['data']['id']);

        $response->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals($user->id, $orderCreated->user_id);
    }
}
