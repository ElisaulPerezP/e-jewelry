<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(): view
    {
        return view('roles.index');
    }
    public function assignPermissionsToRole(Role $role): view
    {
        return view('roles.assignPermissions', ['resource_type' => 'role', 'id' => $role->id]);
    }
}
