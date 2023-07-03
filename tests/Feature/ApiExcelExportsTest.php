<?php

namespace Tests\Feature;

use App\Imports\ProductsImport;
use App\Models\Import;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        Product::factory(2)->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.export.products');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $permission->assignRole($role);
        $response = $this->actingAs($admin, 'api')
            ->get('api/export/products/');
        $response->assertOk();
        self::assertTrue(file_exists($response->baseResponse->getFile()->getRealPath()));
    }
    public function testAdminCanImportProducts()
    {
        $file = UploadedFile::fake()->create('test.xlsx', 100);
        $data = [
            'name' => 'TestFile',
            'comments' => 'Aditional Coments',
            'file' => $file,
        ];
        Excel::fake();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('api.export.products');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $permission->assignRole($role);
        $response = $this->actingAs($admin, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('api/import/products/', $data, [
                'file' => $file,
            ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('imports', 1);
        $import = Import::find(1);

        Excel::assertImported(storage_path('/app/public/importes/' . $import->file_uuid));

        Excel::assertImported(storage_path('/app/public/importes/' . $import->file_uuid), function (ProductsImport $import) {
            return true;
        });
    }
}
