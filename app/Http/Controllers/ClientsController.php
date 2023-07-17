<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientsController extends Controller
{
    public function index(): view
    {
        return view('permissions.clientIndex');
    }
    public function showCode(Request $request): view
    {
        $code = $request->query('code');

        return view('permissions.clientToken', ['code' => $code]);
    }
}
