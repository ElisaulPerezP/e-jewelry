<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PermissionsController extends Controller
{
    public function index(): view
    {
        return view('permissions.index');
    }
}
