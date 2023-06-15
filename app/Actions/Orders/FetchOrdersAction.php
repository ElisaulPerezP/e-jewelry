<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class FetchOrdersAction
{
    public function execute(): AnonymousResourceCollection
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

    private function checkStatus(Order $order): void
    {
        $service = new PlaceToPayPaymentService();
        $service->getRequestInformation($order);
    }
}
