<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testItCanShowAllUsers(): void
    {
        User::factory(1000)->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $this->assertAuthenticated();
        $this->assertTrue(Cache::has('users'));
        $this->assertEquals(Cache::get('users'), User::select('id', 'name', 'email', 'status')->paginate(10));
        $response->assertOk();
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
        $this->assertDatabaseCount('users', 1001);
    }

    public function testItCanShowEditView(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('edit.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->get(route('users.edit', $user));

        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticated();
        $response->assertOk();
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user');
    }

    public function testItCanUpdateUser(): void
    {
        Cache::rememberForever('users', function () {
            return 'users';
        });

        $user = User::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('update.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'testingName',
            'email' => 'testing@test.com',
        ]);

        $userUpdated = User::findOrFail($user->id);

        $response->assertRedirect();
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 2);
        $this->assertFalse(Cache::has('users'));
        $this->assertEquals('testingName', $userUpdated->name);
        $this->assertEquals('testing@test.com', $userUpdated->email);
    }

    public function testItCanChangeUserStatus(): void
    {
        Cache::rememberForever('users', function () {
            return 'users';
        });

        $user = User::factory()->create();
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('changeStatus.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->put(route('users.changeStatus', $user));

        $userChanged = User::findOrFail($user->id);

        $response->assertRedirect();
        $this->assertFalse(Cache::has('users'));
        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticated();
        $this->assertEquals(0, $userChanged->status);

        $this->actingAs($admin)->put(route('users.changeStatus', $user));

        $userChanged = User::findOrFail($user->id);

        $this->assertEquals(1, $userChanged->status);
    }
    public function testItCanShowDetailView(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('show.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->get(route('users.show', $user));

        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticated();
        $response->assertOk();
        $response->assertViewIs('users.show');
        $response->assertViewHas('user');
    }

    public function testItCantShowAllUsersWhenUserIsUnable(): void
    {
        $admin = User::factory()->create([
            'status' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function testItCanLogAllVisits(): void
    {
        $guest = User::factory()->create();

        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $file = storage_path('logs/visits.log');
        $lineCountInitial = count(file($file));
        $this->actingAs($admin)->get(route('users.index'));
        $lineCountFinal = count(file($file));
        $this->assertEquals($lineCountInitial + 1, $lineCountFinal);

        $lineCountInitial = count(file($file));
        $this->actingAs($guest)->get(route('welcome'));
        $lineCountFinal = count(file($file));
        $this->assertEquals($lineCountInitial + 1, $lineCountFinal);
    }
}
