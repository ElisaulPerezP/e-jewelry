<?php

namespace App\Http\Controllers;

use App\Http\Payment\request\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\CartItem;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiOrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $orders = Cache::rememberForever('orders', function () {
            return auth()->user()->orders;
        });

        return OrderResource::collection($orders);
    }

    public function update(OrderRequest $request, Order $order): OrderResource|string
    {
        $order->state = $request->state;
        $order->save();

        Cache::forget('orders');

        return new OrderResource($order);
    }

    public function store(Request $request): OrderResource
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

        $service = new PlaceToPayPayment();
        $order = $service->pay($order, $request->ip(), $request->userAgent());
        Cache::forget('orders');

        return new OrderResource($order);
    }

    /**
     * @throws Exception
     */
    public function checkStatus(Order $order): OrderResource
    {
        if ($order->state === 'pending') {
            $service = new PlaceToPayPayment();
            $service->getRequestInformation($order);
        }

        return new OrderResource($order);
    }
}
