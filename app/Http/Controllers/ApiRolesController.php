<?php

namespace App\Http\Controllers;

use App\Actions\Permissions\CreateRoleAction;
use App\Actions\Permissions\DeleteRoleAction;
use App\Actions\Permissions\GetPaginatedPermissionsToRoleAction;
use App\Actions\Permissions\GetPaginatedPermissionsToUserAction;
use App\Actions\Permissions\GetPaginatedRolesAction;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\RoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class ApiRolesController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetPaginatedRolesAction())($request);
    }
    public function showPermissionsToRole(IndexRequest $request, Role $role): AnonymousResourceCollection
    {
        return (new GetPaginatedPermissionsToRoleAction())($request, $role);
    }
    public function showPermissionsToUser(IndexRequest $request, User $user): AnonymousResourceCollection
    {
        return (new GetPaginatedPermissionsToUserAction())($request, $user);
    }
    public function store(RoleRequest $request): JsonResponse
    {
        return (new CreateRoleAction())($request);
    }

    public function delete(Role $role): JsonResponse
    {
        return (new DeleteRoleAction())($role);
    }
    public function roleIdentity(Role $role): Role
    {
        return $role;
    }
    public function userIdentity(User $user): User
    {
        return $user;
    }
}
