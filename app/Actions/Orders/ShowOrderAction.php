<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;

class ShowOrderAction
{
    public function __invoke(Order $order): OrderResource
    {
        if ($order->state === 'pending') {
            ( new CheckOrderStatusAction())($order);
        }

        return new OrderResource($order);
    }
}
