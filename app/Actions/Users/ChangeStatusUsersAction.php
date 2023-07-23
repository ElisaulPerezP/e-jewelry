<?php

namespace App\Actions\Users;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ChangeStatusUsersAction
{
    public function __invoke(User $user): UserResource
    {
        Cache::forget('users');
        $user->status = !$user->status;
        $user->save();

        return new UserResource($user);
    }
}
