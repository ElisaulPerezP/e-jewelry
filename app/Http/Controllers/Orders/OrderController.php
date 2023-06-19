<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
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
