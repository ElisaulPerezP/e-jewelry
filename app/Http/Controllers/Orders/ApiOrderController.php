<?php

namespace App\Http\Controllers\Orders;

use App\Actions\Orders\CheckOrderStatusAction;
use App\Actions\Orders\CreateOrderAction;
use App\Actions\Orders\FetchOrdersAction;
use App\Actions\Orders\RetryOrderAction;
use App\Actions\Orders\ShowOrderAction;
use App\Actions\Orders\UpdateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Orders\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiOrderController extends Controller
{
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new FetchOrdersAction())($request);
    }

    public function show(Order $order): OrderResource
    {
        return (new ShowOrderAction())($order);
    }

    public function update(OrderRequest $request, Order $order): OrderResource|string
    {
        return (new UpdateOrderAction())($order, $request);
    }

    public function store(Request $request): OrderResource
    {
        return (new CreateOrderAction())($request);
    }

    /**
     * @throws Exception
     */
    public function checkStatus(Order $order): OrderResource
    {
        return (new CheckOrderStatusAction())($order);
    }

    public function retry(Request $request, Order $order): OrderResource|JsonResponse
    {
        return (new RetryOrderAction())($request, $order);
    }
}
