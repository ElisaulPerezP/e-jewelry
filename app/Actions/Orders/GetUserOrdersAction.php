<?php

namespace App\Actions\Orders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUserOrdersAction
{
    public function execute(): AnonymousResourceCollection
    {
        Cache::forget('orders');
        $orders = Cache::rememberForever('orders', function () {
            return Auth::user()->orders;
        });

        foreach ($orders as $order) {
            if ($order->state === 'pending') {
                $this->checkStatus($order);
            }
        }

        return OrderResource::collection($orders);
    }

}
