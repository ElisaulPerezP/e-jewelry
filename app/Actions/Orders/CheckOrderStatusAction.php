<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;

class CheckOrderStatusAction
{
    public function __invoke(Order $order): OrderResource
    {
        $service = new PlaceToPayPaymentService();
        $service->getRequestInformation($order);

        return new OrderResource($order);
    }
}
