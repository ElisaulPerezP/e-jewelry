<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanRetrieveProductsIndex(): void
    {
        Product::factory(3)->create();

        $response = $this->get(route('api.products.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'stock',
                    'score',
                    'status',
                    'barCode',
                ],
            ],
        ]);
        $this->assertDatabaseCount('products', 3);
        $this->assertTrue(Cache::has('products'));
    }

    public function testItCanUpdateProduct(): void
    {
        //$fulanito = User::factory()->create();
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('update.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->putJson(route('api.products.update', $product), [
            'name' => 'testingName',
            'description' => 'testingDescription',
            'price' => '1000',
            'stock' => '1000',
            'score' => '9',
            'barCode' => '1234',
        ]);

        $productUpdated = Product::findOrFail($product->id);

        $response->assertOk();
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('products', 1);
        $this->assertFalse(Cache::has('users'));
        $this->assertFalse(Cache::has('products'));
        $this->assertEquals('testingName', $productUpdated->name);
        $this->assertEquals('testingDescription', $productUpdated->description);
        $this->assertEquals('1000', $productUpdated->price);
        $this->assertEquals('1000', $productUpdated->stock);
        $this->assertEquals('9', $productUpdated->score);
        $this->assertEquals('1234', $productUpdated->barCode);
    }
}
