<?php

namespace Tests\Feature;

use App\Jobs\MailerExportLink;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiAdministrationControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAdministrationView(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.sales.cart');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $response = $this->actingAs($admin, 'web')->getJson(route('administration'));
        $response->assertOk();
        $response->assertViewIs('administration.administration');
    }
    public function testItCanRetrieveCartItemsOnPaidState(): void
    {
        $admin = User::factory()->create();
        $product = Product::factory()->create();
        $permission = Permission::findOrCreate('api.sales.cart');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'paid',
        ]);
        CartItem::factory()->create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'amount' => 10,
            'state' => 'dispatched',
        ]);
        $response = $this->actingAs($admin, 'api')->getJson(route('api.sales.index', ['searching' => '', 'current_page' => '1', 'per_page' => '1', 'flag' => '1']));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'product_id',
                    'amount',
                    'state',
                    'product_image',
                    'product_name',
                    'product_price',
                ],
            ],
        ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertTrue(Cache::has('cartPayed'));
    }

    public function testAdminGenerateReport(): void
    {
        Excel::fake();

        Product::factory(2)->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.export.dispatch');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $this->assertDatabaseCount('reports', 0);

        $response = $this->actingAs($admin, 'api')
            ->get('/api/reports/dispatch');

        $response->assertStatus(302);
        Excel::assertQueuedWithChain([
            new MailerExportLink($admin),
        ]);
        $this->assertDatabaseCount('reports', 1);
    }
    public function testReportView(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('reports.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        $response = $this->actingAs($admin, 'web')->getJson(route('reports.index'));
        $response->assertOk();
        $response->assertViewIs('reports.index');
    }

    public function testItCanRetrieveReports(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.sales.cart');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);
        Report::create([
            'file_path' => 'example.xlsx',
            'user_id' => $admin->id,
        ]);
        $response = $this->actingAs($admin, 'api')->getJson(route('api.reports', ['searching' => '', 'current_page' => '1', 'per_page' => '1', 'flag' => '1']));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'filePath',
                    'user_id',
                    'created_at',
                ],
            ],
        ]);

        $this->assertDatabaseCount('reports', 1);
    }
}
