<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    protected const PERMISSIONS_WEB = [
        'index.user',
        'edit.user',
        'show.user',
        'update.user',
        'changeStatus.user',
        'edit.profile',
        'update.profile',
        'destroy.profile',
        'edit.users.permissions',
    ];

    protected const PERMISSIONS_API = [
        'api.index.product',
        'api.update.product',
        'api.show.product',
        'api.store.product',
        'api.destroy.product',
        'api.changeStatus.product',
        'api.export.product',
        'api.import.product',
    ];

    protected const ROLES = [
        'admin',
        'user',
    ];
    public function run(): void
    {
        foreach (self::PERMISSIONS_WEB as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
        foreach (self::PERMISSIONS_API as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        foreach (self::ROLES as $role) {
            Role::findOrCreate($role, 'web');
        }
        foreach (self::ROLES as $role) {
            Role::findOrCreate($role, 'api');
        }

        $roleApi = Role::findByName('admin', 'api');
        $roleApi->syncPermissions(Permission::whereIn('guard_name', ['api'])->get());
        $roleWeb = Role::findByName('admin', 'web');
        $roleWeb->syncPermissions(Permission::whereIn('guard_name', ['web'])->get());
        $roleUser = Role::findByName('user', 'api');
        $roleUser->syncPermissions('api.index.product');
    }
}
