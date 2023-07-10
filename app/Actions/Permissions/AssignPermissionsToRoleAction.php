<?php

namespace App\Actions\Permissions;

use App\Http\Requests\PermissionsRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissionsToRoleAction
{
    public function execute(PermissionsRequest $request, Role $role): AnonymousResourceCollection
    {
        $permissionsIds = $request->input('params.permissions');
        if ($permissionsIds !== null) {
            if ($role->guard_name === 'api') {
                $role->syncPermissions(Permission::whereIn('id', $permissionsIds)->get());
            }
            if ($role->guard_name === 'web') {
                $role->syncPermissions(Permission::whereIn('id', $permissionsIds)->get());
            }

            return PermissionResource::collection($role->permissions()->get());
        }
        $role->syncPermissions([]);

        return PermissionResource::collection($role->permissions()->get());
    }
}
