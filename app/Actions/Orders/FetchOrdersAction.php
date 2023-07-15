<?php

namespace App\Actions\Orders;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\PlaceToPayPaymentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FetchOrdersAction
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $activeProducts = $request->query('flag', '1');

        Cache::forget('orders');
        $paginatedOrders = Cache::rememberForever('orders', function () use ($currentPage, $perPage, $searching, $activeProducts) {
            $query = Order::where('user_id', Auth::user()->id);

            if ($activeProducts === 1) {
                $query->whereIn('state', ['pending', 'approved']);
            }
            $query->where('reference', 'like', '%' . $searching . '%');

            return $query->paginate($perPage, ['*'], 'page', $currentPage);
        });
        foreach ($paginatedOrders as $order) {
            if ($order->state === 'pending') {
                $this->checkStatus($order);
            }
        }

        return OrderResource::collection($paginatedOrders);
    }

private function checkStatus(Order $order): void
{
    $service = new PlaceToPayPaymentService();
    $service->getRequestInformation($order);
}
}
