<?php

namespace App\Http\Controllers;

use App\Actions\Permissions\AssignPermissionsToRoleAction;
use App\Actions\Permissions\AssignPermissionsToUserAction;
use App\Actions\Permissions\GetPaginatedPermissionsAction;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\PermissionsRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class ApiPermissionController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedPermissionsAction())->execute($request);
    }

    public function assignPermissionsToRole(PermissionsRequest $request, Role $role): AnonymousResourceCollection
    {
        return (new AssignPermissionsToRoleAction())->execute($request, $role);
    }

    public function assignPermissionsToUser(PermissionsRequest $request, User $user): AnonymousResourceCollection
    {
        return (new AssignPermissionsToUserAction())->execute($request, $user);
    }
}
