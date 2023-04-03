<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    protected const PERMISSIONS = [
        'index.user',
        'edit.user',
        'show.user',
        'update.user',
        'changeStatus.user',
        'edit.profile',
        'update.profile',
        'destroy.profile',
    ];

    protected const ROLES = [
        'admin',
    ];
    public function run(): void
    {
        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach (self::ROLES as $role) {
            Role::findOrCreate($role);
        }

        $role = Role::findByName('admin');
        $role->syncPermissions(Permission::all());
    }
}