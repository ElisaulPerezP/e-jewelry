<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testItCanShowAllUsers(): void
    {
        User::factory(100)->create();

        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $this->assertAuthenticated();
        $response->assertOk();
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
        $this->assertDatabaseCount('users', 101);
    }

    public function testItCanShowEditView(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('users.edit', $user));

        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticated();

        $response->assertOk();
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user');
    }

    public function testItCanUpdateUser(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'testingName',
            'email' => 'testing@test.com',
        ]);

        $userUpdated = User::findOrFail($user->id);

        $this->assertDatabaseCount('users', 2);
        $this->assertAuthenticated();

        $response->assertRedirect();
        $this->assertEquals('testingName', $userUpdated->name);
        $this->assertEquals('testing@test.com', $userUpdated->email);
    }
}
