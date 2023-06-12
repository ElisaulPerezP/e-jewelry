<?php

namespace App\Http\Controllers;

use App\Http\Payment\request\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiOrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        Cache::forget('orders');
        $orders = Cache::rememberForever('orders', function () {
            return auth()->user()->orders;
        });

        foreach ($orders as $order) {
            if ($order->state === 'pending') {
                $this->checkStatus($order);
            }
        }

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

        $service = new PlaceToPayPaymentService();
        $service->pay($order, $request->ip(), $request->userAgent());

        Cache::forget('orders');

        return new OrderResource($order);
    }

    /**
     * @throws Exception
     */
    public function checkStatus(Order $order): OrderResource
    {
        $service = new PlaceToPayPaymentService();
        $service->getRequestInformation($order);

        return new OrderResource($order);
    }

    public function retry(Request $request, Order $order): OrderResource|JsonResponse
    {
        if ($order->state !== 'rejected') {
            return response()->json(['error' => 'Esta orden no esta rechazada, el estado es ' . $order->state], 422);
        }

        $newOrder = $order->clone();

        $service = new PlaceToPayPaymentService();
        $service->pay($newOrder, $request->ip(), $request->userAgent());

        return new OrderResource($newOrder);
    }
}
