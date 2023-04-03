<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::select('id', 'name', 'email', 'status')->paginate(10);

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
        $user->name = $request->validated('name');
        $user->email = $request->validated('email');
        $user->save();

        return redirect(route('users.index'));
    }
    public function changeStatus(User $user): RedirectResponse
    {
        $user->status = !$user->status;
        $user->save();

        return redirect(route('users.index'));
    }
}
