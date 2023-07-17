<?php

namespace App\Http\Controllers;

use App\Actions\Permissions\AssignPermissionsToRoleAction;
use App\Actions\Permissions\AssignPermissionsToUserAction;
use App\Actions\Permissions\AssignRolesToUserAction;
use App\Actions\Permissions\GetPaginatedPermissionsAction;
use App\Actions\Permissions\GetPaginatedRolesToUserAction;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\PermissionsRequest;
use App\Http\Requests\Users\RolesRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class ApiPermissionController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedPermissionsAction())($request);
    }

    public function assignPermissionsToRole(PermissionsRequest $request, Role $role): AnonymousResourceCollection
    {
        return (new AssignPermissionsToRoleAction())($request, $role);
    }

    public function assignPermissionsToUser(PermissionsRequest $request, User $user): AnonymousResourceCollection
    {
        return (new AssignPermissionsToUserAction())($request, $user);
    }
    public function showRolesToUser(indexRequest $request, User $user): AnonymousResourceCollection
    {
        return (new GetPaginatedRolesToUserAction())($request, $user);
    }

    public function assignRolesToUser(RolesRequest $request, User $user): AnonymousResourceCollection
    {
        return (new AssignRolesToUserAction())($request, $user);
    }
}
