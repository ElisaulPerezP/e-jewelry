<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanRetrieveProductsIndex(): void
    {
        Product::factory(3)->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.index.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $permission->assignRole($role);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.products.index', ['searching' => '', 'current_page' => 1, 'per_page' => 1, 'flag' => 0]));

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
                    'image',
                ],
            ],
        ]);
        $this->assertDatabaseCount('products', 3);
        $this->assertTrue(Cache::has('products'));
    }

    public function testItCanUpdateProduct(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test-image.jpg')->size(3500);

        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.update.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->putJson(route('api.products.update', $product), [
            '_method' => 'PUT',
            'name' => 'testingName',
            'description' => 'testingDescription',
            'price' => '1000',
            'stock' => '1000',
            'barCode' => '1234',
            'image' => $file,
        ]);

        $productUpdated = Product::findOrFail($product->id);

        $response->assertOk();
        $this->assertAuthenticated();
        $this->assertDatabaseCount('products', 1);
        $this->assertFalse(Cache::has('products'));
        $this->assertEquals('testingName', $productUpdated->name);
        $this->assertEquals('testingDescription', $productUpdated->description);
        $this->assertEquals('1000', $productUpdated->price);
        $this->assertEquals('1000', $productUpdated->stock);
        $this->assertEquals('1234', $productUpdated->barCode);
        Storage::disk('public')->assertExists($productUpdated->image);
    }

    public function testItCanRetrieveAProduct(): void
    {
        $product = Product::factory()->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.show.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.products.show', $product));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'stock',
                'score',
                'status',
                'barCode',
            ],
        ]);
        $this->assertDatabaseCount('products', 1);
    }

    public function testItCanCreateProduct(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test-image.jpg')->size(3500);

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.store.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->postJson(route('api.products.store'), [
            'name' => 'testingName',
            'description' => 'testingDescription',
            'price' => '1000',
            'stock' => '1000',
            'score' => '9',
            'barCode' => '1234',
            'image' => $file,
        ]);

        $productCreated = Product::findOrFail($response->json()['data']['id']);

        $response->assertCreated();
        $this->assertAuthenticated();
        $this->assertDatabaseCount('products', 1);
        $this->assertFalse(Cache::has('products'));
        $this->assertEquals('testingName', $productCreated->name);
        $this->assertEquals('testingDescription', $productCreated->description);
        $this->assertEquals('1000', $productCreated->price);
        $this->assertEquals('1000', $productCreated->stock);
        $this->assertEquals('9', $productCreated->score);
        $this->assertEquals('1234', $productCreated->barCode);
        Storage::disk('public')->assertExists($productCreated->image);
    }

    public function testItCanDeleteProduct(): void
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.destroy.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->deleteJson(route('api.products.destroy', $product));

        $response->assertStatus(204);
        $this->assertAuthenticated();
        $this->assertDatabaseCount('products', 0);
        $this->assertFalse(Cache::has('products'));
    }

    public function testItCanChangeProductStatus(): void
    {
        $product = Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.changeStatus.product');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $productState = $product->status;

        $response = $this->actingAs($admin, 'api')->putJson(route('api.products.changeStatus', $product));

        $productUpdated = Product::findOrFail($response->json()['data']['id']);

        $response->assertOk();
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('products', 1);
        $this->assertFalse(Cache::has('users'));
        $this->assertFalse(Cache::has('products'));
        $this->assertEquals($productUpdated->status, !$productState);
    }
}
