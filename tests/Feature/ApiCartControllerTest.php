<?php

namespace Tests\Feature;

use App\Models\ItemCart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiCartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanRetrieveProductsIndex(): void
    {
        $admin = User::factory()->create();
        $product = Product::factory()->create();
        $permission = Permission::findOrCreate('api.cart.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        ItemCart::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'item_state' => 'in_cart',
        ]);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.cart.index', $admin));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'user_id',
                    'amount',
                    'item_state',
                    'product_image',
                    'product_name',
                    'products_price',
                ],
            ],
        ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertTrue(Cache::has('cart'));
    }

    public function testUpdateCartItem(): void
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.cart.update');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $itemCart = ItemCart::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'item_state' => 'in_cart',
        ]);

        $response = $this->actingAs($admin, 'api')->putJson(route('api.cart.update', $itemCart), [
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 11,
            'item_state' => 'saved',
        ]);

        $productUpdated = ItemCart::findOrFail($itemCart->id);

        $response->assertOk();

        $this->assertDatabaseCount('item_carts', 1);
        $this->assertFalse(Cache::has('cart'));

        $this->assertEquals($admin->id, $productUpdated->user_id);
        $this->assertEquals($product->id, $productUpdated->product_id);
        $this->assertEquals('11', $productUpdated->amount);
        $this->assertEquals('saved', $productUpdated->item_state);
    }

    public function testItCanCreateItemCart(): void
    {

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson(route('api.cart.store', $user->id));

        $itemCartCreated = ItemCart::findOrFail($response->json()['data']['itemCart_id']);

        $response->assertCreated();
        $this->assertDatabaseCount('item_carts', 1);
        $this->assertEquals($user->id, $itemCartCreated->user_id);
        $this->assertEquals($product->id, $itemCartCreated->product_id);
        $this->assertEquals('in_cart', $itemCartCreated->item_state);
    }

    public function testItCanDeleteItemCart(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.destroy.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $product = Product::factory()->create();

        $this->assertDatabaseCount('item_carts', 0);

        $itemCart = ItemCart::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'item_state' => 'in_cart',
        ]);

        $this->assertDatabaseCount('item_carts', 1);

        $response = $this->actingAs($admin, 'api')->deleteJson(route('api.cart.destroy', $itemCart));

        $response->assertStatus(204);
        $this->assertDatabaseCount('item_carts', 0);
        $this->assertFalse(Cache::has('cart'));
    }
}
