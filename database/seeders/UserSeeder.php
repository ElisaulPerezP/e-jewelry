<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@jewelry.com',
        ]);

        $admin->assignRole('admin');

        User::factory()
            ->count(10)
            ->create();
        User::all()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
