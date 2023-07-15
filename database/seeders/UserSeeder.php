<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@jewelry.com',
        ]);

        $roleApiAdmin = Role::findOrCreate('admin', 'api');
        $roleWebAdmin = Role::findOrCreate('admin', 'web');

        $admin->assignRole([$roleApiAdmin, $roleWebAdmin]);

        User::factory()
            ->count(10)
            ->create();
        $roleApiUser = Role::findOrCreate('user', 'api');
        $roleWebUser = Role::findOrCreate('user', 'web');

        User::all()->each(function ($user) use ($roleApiUser, $roleWebUser) {
            $user->assignRole($roleApiUser);
            $user->assignRole($roleWebUser);
        });
    }
}
