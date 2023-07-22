<?php

namespace App\Http\Controllers;

use App\Actions\Users\ChangeStatusUsersAction;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        Cache::forget('users');

        return redirect(route('users.index'));
    }
    public function changeStatus(User $user): UserResource
    {
        return (new ChangeStatusUsersAction())($user);
    }
    public function assignPermissionsToUser(User $user): view
    {
        return view('users.assignPermissions', ['resource_type' => 'user', 'id' => $user->id]);
    }

    public function assignRolesToUser(User $user): view
    {
        return view('users.assignRoles', ['resource_type' => 'user', 'id' => $user->id]);
    }
}
