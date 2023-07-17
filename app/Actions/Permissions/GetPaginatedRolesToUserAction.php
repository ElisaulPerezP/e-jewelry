<?php

namespace App\Actions\Permissions;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\RoleResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetPaginatedRolesToUserAction
{
    public function __invoke(IndexRequest $request, User $user): AnonymousResourceCollection
    {
        Cache::forget('roles');
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $guard = $request->query('flag', '1');

        $paginatedRoles = Cache::rememberForever('roles', function () use ($currentPage, $perPage, $searching, $guard, $user) {
            $query = $user->roles();
            if ($guard) {
                $query->where('guard_name', 'like', 'api');
            }

            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate((int)$perPage, ['*'], 'page', (int)$currentPage);
        });

        return RoleResource::collection($paginatedRoles);
    }
}
