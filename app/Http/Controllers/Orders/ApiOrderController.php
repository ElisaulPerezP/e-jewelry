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
    protected FetchOrdersAction $fetchOrdersAction;
    protected ShowOrderAction $showOrderAction;
    protected UpdateOrderAction $updateOrderAction;
    protected CreateOrderAction $createOrderAction;
    protected CheckOrderStatusAction $checkOrderStatusAction;
    protected RetryOrderAction $retryOrderAction;

    public function __construct(
        FetchOrdersAction $fetchOrdersAction,
        ShowOrderAction $showOrderAction,
        UpdateOrderAction $updateOrderAction,
        CreateOrderAction $createOrderAction,
        CheckOrderStatusAction $checkOrderStatusAction,
        RetryOrderAction $retryOrderAction
    ) {
        $this->fetchOrdersAction = $fetchOrdersAction;
        $this->showOrderAction = $showOrderAction;
        $this->updateOrderAction = $updateOrderAction;
        $this->createOrderAction = $createOrderAction;
        $this->checkOrderStatusAction = $checkOrderStatusAction;
        $this->retryOrderAction = $retryOrderAction;
    }

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $orders = $this->fetchOrdersAction->execute($request);

        return $orders;
    }

    public function show(Order $order): OrderResource
    {
        return $this->showOrderAction->execute($order);
    }

    public function update(OrderRequest $request, Order $order): OrderResource|string
    {
        return $this->updateOrderAction->execute($order, $request);
    }

    public function store(Request $request): OrderResource
    {
        return $this->createOrderAction->execute($request);
    }

    /**
     * @throws Exception
     */
    public function checkStatus(Order $order): OrderResource
    {
        return $this->checkOrderStatusAction->execute($order);
    }

    public function retry(Request $request, Order $order): OrderResource|JsonResponse
    {
        return $this->retryOrderAction->execute($request, $order);
    }
}
