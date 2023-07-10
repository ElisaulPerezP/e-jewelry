<?php

namespace App\Actions\Permissions;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetPaginatedPermissionsAction
{
    public function execute(IndexRequest $request): AnonymousResourceCollection
    {
        Cache::forget('permissions');
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $guardPermissions = $request->query('flag', '1');

        $paginatedPermissions = Cache::rememberForever('permissions', function () use ($currentPage, $perPage, $searching, $guardPermissions) {
            $query = DB::table('permissions');
            if ($guardPermissions !== '0') {
                $query->where('guard_name', 'like', $guardPermissions);
            }
            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate((int)$perPage, ['*'], 'page', (int)$currentPage);
        });

        return PermissionResource::collection($paginatedPermissions);
    }
}
