<?php

namespace App\Actions\Permissions;

use App\Http\Requests\PermissionsRequest;
use App\Http\Resources\PermissionResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToUserAction
{
    public function __invoke(PermissionsRequest $request, User $user): AnonymousResourceCollection
    {
        $permissionsIds = $request->input('params.permissions');

        $user->syncPermissions(Permission::whereIn('id', $permissionsIds)->get());

        return PermissionResource::collection($user->permissions()->get());
    }
}
