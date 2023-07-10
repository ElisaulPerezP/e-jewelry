<?php

namespace App\Actions\Permissions;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\PermissionResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetPaginatedPermissionsToUserAction
{
    public function execute(IndexRequest $request, User $user): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        Cache::forget('permissionsOnUser');
        $paginatedPermissions = Cache::rememberForever('permissionsOnUser', function () use ($currentPage, $perPage, $searching, $user) {
            $query = $user->permissions();

            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate((int)$perPage, ['*'], 'page', (int)$currentPage);
        });

        return PermissionResource::collection($paginatedPermissions);
    }
}
