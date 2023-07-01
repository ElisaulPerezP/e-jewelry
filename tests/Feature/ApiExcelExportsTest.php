<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiExcelExportsTest extends TestCase
{
    use RefreshDatabase;
    public function testAdminCanDownLoadExcelExport(): void
    {
        Excel::fake();
        Product::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.export.products');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $permission->assignRole($role);
        $response = $this->actingAs($admin, 'api')
            ->get('/api/export/products/');
        $response->assertOk();
    }
}
