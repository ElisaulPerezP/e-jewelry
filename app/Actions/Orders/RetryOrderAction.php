<?php

namespace App\Actions\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RetryOrderAction
{
    public function __invoke(Request $request, Order $order): OrderResource|JsonResponse
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
