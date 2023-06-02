<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(User $user): View
    {
        return view('cart.index', compact('user'));
    }

}
