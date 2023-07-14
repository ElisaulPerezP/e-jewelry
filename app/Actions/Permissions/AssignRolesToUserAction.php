<?php

namespace App\Actions\Permissions;

use App\Http\Requests\Users\RolesRequest;
use App\Http\Resources\RoleResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class AssignRolesToUserAction
{
    public function execute(RolesRequest $request, User $user): AnonymousResourceCollection
    {
        $rolesIds = $request->input('params.roles');

        $user->syncRoles(Role::whereIn('id', $rolesIds)->get());

        return RoleResource::collection($user->roles()->get());
    }
}
