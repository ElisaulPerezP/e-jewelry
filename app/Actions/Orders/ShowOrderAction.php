<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;

class ShowOrderAction
{
    private CheckOrderStatusAction $checkOrderStatusAction;

    public function __construct(CheckOrderStatusAction $checkOrderStatusAction)
    {
        $this->checkOrderStatusAction = $checkOrderStatusAction;
    }
    public function execute(Order $order): OrderResource
    {
        if ($order->state === 'pending') {
            $this->checkOrderStatusAction->execute($order);
        }

        return new OrderResource($order);
    }
}
