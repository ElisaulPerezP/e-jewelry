<?php

namespace App\Actions\Users;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetPaginatedUsersAction
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        Cache::forget('users');
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $activeUsers = $request->query('flag', '1');

        $paginatedUsers = Cache::rememberForever('users', function () use ($currentPage, $perPage, $searching, $activeUsers) {
            $query = User::query();

            if ($activeUsers) {
                $query->where('status', 1);
            }

            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate((int)$perPage, ['id', 'name', 'email', 'status'], 'page', (int)$currentPage);
        });

        return UserResource::collection($paginatedUsers);
    }
}
