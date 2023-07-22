<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiPermissionControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testItCanShowAllPermissions(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('permissions.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.permissions.index', ['searching' => '', 'current_page' => 1, 'per_page' => 1, 'flag' => 0]));

        $this->assertAuthenticated();
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertDatabaseCount('permissions', 1);
        $this->assertTrue(Cache::has('permissions'));
    }
    public function testItCanShowAllRoles(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('roles.index');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->getJson(route('api.roles.index', ['searching' => '', 'current_page' => 1, 'per_page' => 1, 'flag' => 0]));

        $this->assertAuthenticated();
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertDatabaseCount('permissions', 1);
        $this->assertTrue(Cache::has('roles'));
    }
    public function testItCanCreateARolesApiGuard(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('role.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->postJson(route('api.roles.store', ['name' => 'testRole', 'guardApi' => 'true', 'guardWeb' => 'false']));

        $this->assertAuthenticated();
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'roles' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertEquals('api', $response->json()['roles'][0]['guard_name']);

        $this->assertDatabaseCount('roles', 2);
        $this->assertFalse(Cache::has('roles'));
    }

    public function testItCanCreateARolesWebGuard(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('role.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin, 'api')->postJson(route('api.roles.store', ['name' => 'testRole', 'guardApi' => 'false', 'guardWeb' => 'true']));

        $this->assertAuthenticated();
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'roles' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertEquals('web', $response->json()['roles'][0]['guard_name']);

        $this->assertDatabaseCount('roles', 2);
        $this->assertFalse(Cache::has('roles'));
    }
    public function testItCanDeleteARole(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('role.delete');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $roleForTest = Role::findOrCreate('testRole')->givePermissionTo($permission);

        $response = $this->actingAs($admin, 'api')->delete(route('api.roles.delete', $roleForTest));

        $this->assertAuthenticated();
        $response->assertOK();
        $this->assertEquals('Role deleted successfully', $response->json()['message']);

        $this->assertDatabaseCount('roles', 1);
        $this->assertFalse(Cache::has('roles'));
    }

    public function testItCanRetrieveRolePermissions(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('role.permissions');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');

        $roleForTest = Role::findOrCreate('testRole')->givePermissionTo($permissionForTest);
        $response = $this->actingAs($admin, 'api')->get(route('api.roles.permissions', [$roleForTest, 'searching' => '', 'current_page' => 1, 'per_page' => 1000, 'flag' => 0]));

        $this->assertAuthenticated();
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->assertEquals('test.permission', $response->json()['data'][0]['name']);
        $this->assertDatabaseCount('roles', 2);
        $this->assertTrue(Cache::has('permissionsOnRole'));
    }

    public function testItCanRetrieveUserPermissions(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('users.permissions');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');
        $userForTest = User::factory()->create();
        $userForTest->givePermissionTo($permissionForTest);
        $response = $this->actingAs($admin, 'api')->get(route('api.users.permissions', [$userForTest, 'searching' => '', 'current_page' => 1, 'per_page' => 1000, 'flag' => 0]));

        $this->assertAuthenticated();
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->assertEquals('permissions.test.permission', $response->json()['data'][0]['name']);

        $this->assertTrue(Cache::has('permissionsOnUser'));
    }

    public function testItCanAssignPermissionsToRole(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('roles.permissions.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');
        $roleForTest = Role::findOrCreate('roleForTest');

        $permissionsIds = [$permissionForTest->id];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.roles.permissions.store', [$roleForTest]),
            $parameters
        );

        $this->assertAuthenticated();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertTrue($roleForTest->hasPermissionTo($permissionForTest));
    }

    public function testItCanRevokePermissionsToRole(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('roles.permissions.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');
        $roleForTest = Role::findOrCreate('roleForTest');

        $permissionsIds = [$permissionForTest->id];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $this->actingAs($admin, 'api')->post(
            route('api.roles.permissions.store', [$roleForTest]),
            $parameters
        );

        $this->assertTrue($roleForTest->hasPermissionTo($permissionForTest));

        $permissionsIds = [];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $this->actingAs($admin, 'api')->post(
            route('api.roles.permissions.store', [$roleForTest]),
            $parameters
        );
        $hasPermissions = $roleForTest->permissions()->exists();
        $this->assertFalse($hasPermissions);
    }

    public function testItCanAssignPermissionsToUser(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('users.permissions.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');
        $userForTest = User::factory()->create();

        $permissionsIds = [$permissionForTest->id];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.users.permissions.store', [$userForTest]),
            $parameters
        );

        $this->assertAuthenticated();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertTrue($userForTest->hasPermissionTo($permissionForTest));
    }

    public function testItCanRevokePermissionsToUser(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('users.permissions.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $permissionForTest = Permission::findOrCreate('test.permission');
        $userForTest = User::factory()->create();

        $permissionsIds = [$permissionForTest->id];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.users.permissions.store', [$userForTest]),
            $parameters
        );

        $this->assertAuthenticated();
        $response->assertStatus(200);
        $this->assertTrue($userForTest->hasPermissionTo($permissionForTest));

        $permissionsIds = [];
        $requestData = ['permissions' => $permissionsIds];
        $parameters = ['params' => $requestData];

        $this->actingAs($admin, 'api')->post(
            route('api.users.permissions.store', [$userForTest]),
            $parameters
        );
        $hasPermissions = $userForTest->permissions()->exists();
        $this->assertFalse($hasPermissions);
    }

    public function testItCanAssignRolesToUser(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('users.roles.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $RoleForTest = Role::findOrCreate('RoleForTest');
        $userForTest = User::factory()->create();
        $rolesIds = [$RoleForTest->id];
        $requestData = ['roles' => $rolesIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.users.roles.store', $userForTest),
            $parameters
        );
        $this->assertAuthenticated();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'guard_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertTrue($userForTest->hasRole($RoleForTest));
    }

    public function testItCanRevokeRolesToUser(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('users.roles.store');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $RoleForTest = Role::findOrCreate('RoleForTest');
        $userForTest = User::factory()->create();
        $rolesIds = [$RoleForTest->id];
        $requestData = ['roles' => $rolesIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.users.roles.store', $userForTest),
            $parameters
        );
        $this->assertAuthenticated();
        $response->assertStatus(200);
        $this->assertTrue($userForTest->hasRole($RoleForTest));

        $rolesIds = [];
        $requestData = ['roles' => $rolesIds];
        $parameters = ['params' => $requestData];

        $response = $this->actingAs($admin, 'api')->post(
            route('api.users.roles.store', $userForTest),
            $parameters
        );
        $hasRoles = $userForTest->roles()->exists();
        $this->assertFalse($hasRoles);
    }

    public function testItCanRetrieveResourceIdentity(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('identity');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $roleForTest = Role::findOrCreate('roleForTest');
        $userForTest = User::factory()->create();

        $responseRole = $this->actingAs($admin, 'api')->get(
            route('api.identity.role', [$roleForTest])
        );
        $responseRole->assertStatus(200);

        $this->assertEquals('roleForTest', $responseRole->json()['name']);

        $responseUser = $this->actingAs($admin, 'api')->get(
            route('api.identity.user', [$userForTest])
        );
        $responseUser->assertStatus(200);

        $this->assertEquals($userForTest->name, $responseUser->json()['name']);
    }
}
