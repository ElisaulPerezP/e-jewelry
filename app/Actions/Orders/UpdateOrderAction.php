<?php

namespace App\Actions\Orders;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class UpdateOrderAction
{
    public function execute(Order $order, OrderRequest $request): OrderResource
    {
        $order->state = $request->state;
        $order->save();

        Cache::forget('orders');

        return new OrderResource($order);
    }
}
