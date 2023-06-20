<?php

namespace App\Http\Controllers\CartItems;

use App\Actions\CartItem\AddCartItemAction;
use App\Actions\CartItem\ChangeCartItemStateAction;
use App\Actions\CartItem\DeleteCartItemAction;
use App\Actions\CartItem\GetCartItemsAction;
use App\Actions\CartItem\SetCartItemAmountAction;
use App\Actions\CartItem\TotalRetriveAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartItem\AmountCartItemRequest;
use App\Http\Requests\CartItem\StateCartItemRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiCartController extends Controller
{
    protected GetCartItemsAction $getCartItemsAction;
    protected AddCartItemAction $addCartItemAction;
    protected TotalRetriveAction $totalRetriveAction;
    protected ChangeCartItemStateAction $changeCartItemStateAction;
    protected SetCartItemAmountAction $setCartItemAmountAction;
    protected DeleteCartItemAction $deleteCartItemAction;

    public function __construct(
        GetCartItemsAction $getCartItemsAction,
        AddCartItemAction $addCartItemAction,
        TotalRetriveAction $totalRetriveAction,
        ChangeCartItemStateAction $changeCartItemStateAction,
        SetCartItemAmountAction $setCartItemAmountAction,
        DeleteCartItemAction $deleteCartItemAction
    ) {
        $this->getCartItemsAction = $getCartItemsAction;
        $this->addCartItemAction = $addCartItemAction;
        $this->totalRetriveAction = $totalRetriveAction;
        $this->changeCartItemStateAction = $changeCartItemStateAction;
        $this->setCartItemAmountAction = $setCartItemAmountAction;
        $this->deleteCartItemAction = $deleteCartItemAction;
    }

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return $this->getCartItemsAction->execute($request);
    }

    public function store(Product $product): CartItemResource
    {
        return $this->addCartItemAction->execute($product);
    }

    public function total(): JsonResponse
    {
        return $this->totalRetriveAction->execute();
    }

    public function changeState(StateCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        return $this->changeCartItemStateAction->execute($request, $cartItem);
    }

    public function setAmount(AmountCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        return $this->setCartItemAmountAction->execute($request, $cartItem);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        return $this->deleteCartItemAction->execute($cartItem);
    }
}
