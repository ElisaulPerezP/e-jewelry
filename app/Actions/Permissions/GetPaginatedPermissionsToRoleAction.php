<?php

namespace App\Actions\Permissions;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class GetPaginatedPermissionsToRoleAction
{
    public function execute(IndexRequest $request, Role $role): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        Cache::forget('permissionsOnRole');
        $paginatedPermissions = Cache::rememberForever('permissionsOnRole', function () use ($currentPage, $perPage, $searching, $role) {
            $query = $role->permissions();

            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate((int)$perPage, ['*'], 'page', (int)$currentPage);
        });

        return RoleResource::collection($paginatedPermissions);
    }
}
