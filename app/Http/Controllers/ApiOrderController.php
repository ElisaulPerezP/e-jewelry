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

    public function update(OrderRequest $request): OrderResource|string
    {
        $order = Order::find($request->order_id);
        $order->order_state = $request->order_state;
        $order->save();
        Cache::forget('orders');

        return new OrderResource($order);
    }

    public function store(Request $request): OrderResource
    {
        $total = 0;
        $description = '';
        foreach ($request->items_cart as $item) {
            $product = Product::find($item['product_id']);
            $total += $item['amount'] * $product->price;
            $description .= $product->name . ', ';
        }
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->payment_reference = uuid_create();
        $order->description = 'Los productos que esta pagando son: ' . $description;
        $order->total = $total;
        $order->currency = 'COP';
        $order->order_state = 'processing';
        $order->expiration = date('c', strtotime(date('c') . ' + 1 hour'));
        $order->return_url = 'http://127.0.0.1:8000/order/state/' . $order->payment_reference;
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

            return new OrderResource($order);
        } else {
            return new OrderResource($order);
        }
    }
}
