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
        'edit.product',
        'user.dev',
    ];

    protected const PERMISSIONS_API = [
        'api.index.product',
        'api.update.product',
        'api.show.product',
        'api.store.product',
        'api.destroy.product',
        'api.changeStatus.product',
        'api.export.products',
        'api.import.products',
        'api.index.cart',
        'api.setAmount.cart',
        'api.store.cart',
        'api.destroy.cart',
        'api.changeState.cart',
        'api.total.cart',
        'api.order.index',
        'api.order.show',
        'api.order.store',
        'api.order.retry',
        'api.permissions.index',
        'api.roles.delete',
        'api.permissionsToRole.show',
        'api.roles.store',
        'api.roles.index',
        'api.roles.assignPermissions',
        'api.user.showPermissions',
        'api.user.assignPermissions',
        'api.user.showRoles',
        'api.user.assignRoles',
        'api.role.identity',
        'api.user.identity',
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

        $roleAdminApi = Role::findByName('admin', 'api');
        $roleAdminApi->syncPermissions(Permission::whereIn('guard_name', ['api'])->get()->pluck('name')->toArray());
        $roleAdminWeb = Role::findByName('admin', 'web');
        $roleAdminWeb->syncPermissions(Permission::whereIn('guard_name', ['web'])->get()->pluck('name')->toArray());

        $roleUserApi = Role::findByName('user', 'api');
        $roleUserApi->syncPermissions(
            'api.index.product',
            'api.show.product',
            'api.index.cart',
            'api.setAmount.cart',
            'api.store.cart',
            'api.destroy.cart',
            'api.changeState.cart',
            'api.total.cart',
            'api.order.index',
            'api.order.show',
            'api.order.store',
            'api.order.retry'
        );
    }
}
