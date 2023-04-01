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
}
