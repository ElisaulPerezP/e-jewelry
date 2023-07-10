<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = Cache::rememberForever('users', function () {
            return User::select('id', 'name', 'email', 'status')->paginate(10);
        });

        return view('users.index', compact('users'));
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
    public function changeStatus(User $user): RedirectResponse
    {
        $user->status = !$user->status;
        $user->save();

        Cache::forget('users');

        return redirect(route('users.index'));
    }
    public function assignPermissionsToUser(User $user): view
    {
        return view('users.assignPermissions', ['resource_type' => 'user', 'id' => $user->id]);
    }
}
