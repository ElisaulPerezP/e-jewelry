<?php

namespace Tests\Feature;

use App\Models\CartItem;
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
        CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'in_cart',
        ]);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.cart.index', ['searching' => '', 'current_page' => '1', 'per_page' => '1', 'flag' => '0']));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'user_id',
                    'amount',
                    'state',
                    'product_image',
                    'product_name',
                    'product_price',
                ],
            ],
        ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertTrue(Cache::has('cart'));
    }

    public function testUpdateCartItemAmount(): void
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.setAmount.cart');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $cartItem = CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'in_cart',
        ]);

        $response = $this->actingAs($admin, 'api')->putJson(route('api.cart.setAmount', $cartItem), [
            'amount' => '11',

        ]);

        $productUpdated = CartItem::findOrFail($cartItem->id);

        $response->assertOk();

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertFalse(Cache::has('cart'));

        $this->assertEquals($admin->id, $productUpdated->user_id);
        $this->assertEquals($product->id, $productUpdated->product_id);
        $this->assertEquals('11', $productUpdated->amount);
        $this->assertEquals('in_cart', $productUpdated->state);
    }

    public function testUpdateCartItemStateToInCart(): void
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.cart.changeState');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $cartItem = CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'selected',
        ]);

        $options = ['in_cart', 'in_order', 'selected'];

        foreach ($options as $option) {
            $response = $this->actingAs($admin, 'api')->putJson(route('api.cart.update.state.saved', $cartItem->id), ['state' => $option]);

            $productUpdated = CartItem::findOrFail($cartItem->id);

            $response->assertOk();

            $this->assertDatabaseCount('cart_items', 1);
            $this->assertFalse(Cache::has('cart'));

            $this->assertEquals($admin->id, $productUpdated->user_id);
            $this->assertEquals($product->id, $productUpdated->product_id);
            $this->assertEquals($cartItem->amount, $productUpdated->amount);
            $this->assertEquals($option, $productUpdated->state);
        }
    }

    public function testItCanCreateItemCart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $permission = Permission::findOrCreate('api.store.cart', 'api');
        $role = Role::findOrCreate('user', 'api')->givePermissionTo($permission);
        $user->assignRole($role);
        $response = $this->actingAs($user, 'api')->postJson(route('api.cart.store', $user->id));

        $cartItemCreated = CartItem::findOrFail($response->json()['data']['id']);

        $response->assertCreated();
        $this->assertDatabaseCount('cart_items', 1);
        $this->assertEquals($user->id, $cartItemCreated->user_id);
        $this->assertEquals($product->id, $cartItemCreated->product_id);
        $this->assertEquals('in_cart', $cartItemCreated->state);
    }

    public function testItCanDeleteItemCart(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.destroy.cart', 'api');
        $role = Role::findOrCreate('admin', 'api')->givePermissionTo($permission);
        $admin->assignRole($role);
        $product = Product::factory()->create();

        $this->assertDatabaseCount('cart_items', 0);

        $CartItem = CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'in_cart',
        ]);

        $this->assertDatabaseCount('cart_items', 1);

        $response = $this->actingAs($admin, 'api')->deleteJson(route('api.cart.destroy', $CartItem));

        $response->assertStatus(204);
        $this->assertDatabaseCount('cart_items', 0);
        $this->assertFalse(Cache::has('cart'));
    }
}
