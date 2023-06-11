<?php

namespace App\Http\Controllers;

use App\Http\Payment\request\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\ItemCart;
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

        $itemsCart = ItemCart::where('state', 'selected')
            ->where('user_id', auth()->user()->id)
            ->get();

        $order = new Order();

        foreach ($itemsCart as $itemCart) {
            $itemCart->state = 'in_order';
            $itemCart->order_id = $order->id;
            $itemCart->save();
            $order->total += $itemCart->amount * $itemCart->product->price;
        }

        $order->user_id = auth()->user()->id;
        $order->reference = uuid_create();
        $order->currency = 'COP';
        $order->state = 'pending';
        $order->return_url = 'http://127.0.0.1:8000/order/state/' . $order->reference;
        $order->save();

        $service = new PlaceToPayPayment();
        $order = $service->pay($order, $request->ip(), $request->userAgent());
        Cache::forget('orders');

        return new OrderResource($order);
    }

    /**
     * @throws Exception
     */
    public function checkStatus(Order $order): OrderResource|string
    {
        if ($order->order_state === 'processing') {
            $service = new PlaceToPayPayment();
            $service->getRequestInformation($order);
        }

        return new OrderResource($order);
    }
}
