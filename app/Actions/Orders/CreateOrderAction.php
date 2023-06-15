<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreateOrderAction
{
    public function execute(Request $request): OrderResource
    {
        $cartItems = CartItem::where('state', 'selected')
            ->where('user_id', auth()->user()->id)
            ->get();

        $order = new Order();

        $order->user_id = auth()->user()->id;
        $order->reference = uuid_create();
        $order->currency = 'COP';
        $order->state = 'pending';
        $order->return_url = 'http://127.0.0.1:8000/order/state/' . $order->reference;
        $order->save();

        foreach ($cartItems as $cartItem) {
            $cartItem->state = 'in_order';
            $cartItem->order_id = $order->id;
            $cartItem->save();
        }

        $order->setTotal();

        $service = new PlaceToPayPaymentService();
        $service->pay($order, $request->ip(), $request->userAgent());

        Cache::forget('orders');

        return new OrderResource($order);
    }
}
