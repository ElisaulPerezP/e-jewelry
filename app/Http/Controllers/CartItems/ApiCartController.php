<?php

namespace App\Http\Controllers\CartItems;

use App\Actions\CartItem\ChangeCartItemStateAction;
use App\Actions\CartItem\DeleteCartItemAction;
use App\Actions\CartItem\GetCartItemsAction;
use App\Actions\CartItem\SetCartItemAmountAction;
use App\Actions\CartItem\StoreCartItemAction;
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
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        return (new GetCartItemsAction())($request);
    }

    public function store(Product $product): CartItemResource
    {
        return (new StoreCartItemAction())($product);
    }

    public function total(): JsonResponse
    {
        return (new TotalRetriveAction())();
    }

    public function changeState(StateCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        return (new ChangeCartItemStateAction())($request, $cartItem);
    }

    public function setAmount(AmountCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        return (new SetCartItemAmountAction())($request, $cartItem);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        return (new DeleteCartItemAction())($cartItem);
    }
}
