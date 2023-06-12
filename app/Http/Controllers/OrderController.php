<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('order.index');
    }
    public function show(Order $order): View
    {
        return view('order.show', ['id' => $order->id]);
    }
}
