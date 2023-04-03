<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this
            ->actingAs($admin)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this
            ->actingAs($admin)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $admin->refresh();

        $this->assertSame('Test User', $admin->name);
        $this->assertSame('test@example.com', $admin->email);
        $this->assertNull($admin->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this
            ->actingAs($admin)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $admin->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($admin->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this
            ->actingAs($admin)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($admin->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $admin = User::factory()->create();
        $permission = Permission::findOrCreate('index.user');
        $role = Role::findOrCreate('admin')->givePermissionTo($permission);
        $admin->assignRole($role);

        $response = $this
            ->actingAs($admin)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($admin->fresh());
    }
}
