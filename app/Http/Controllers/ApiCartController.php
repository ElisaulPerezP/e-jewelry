<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItem\AmountCartItemRequest;
use App\Http\Requests\CartItem\StateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiCartController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        Cache::forget('cart');
        $itemsCart = Cache::rememberForever('cart', function () {
            return auth()->user()->cartItems->whereIn('state', ['selected', 'in_cart']);
        });

        return CartItemResource::collection($itemsCart);
    }

    public function store(Product $product): CartItemResource
    {
        $cartItem = new CartItem();
        $cartItem->user_id = auth()->user()->id;
        $cartItem->product_id = $product->id;
        $cartItem->amount = 1;
        $cartItem->state = 'in_cart';
        $cartItem->save();

        $product->stock--;
        $product->save();

        Cache::forget('cart');
        Cache::forget('products');

        return new CartItemResource($cartItem);
    }

    public function changeState(StateCartItemRequest $request, CartItem $cartItem): CartItemResource
    {
        $cartItem->state = $request->state;
        $cartItem->save();

        Cache::forget('cart');

        return new CartItemResource($cartItem);
    }

    public function setAmount(AmountCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        $product = $cartItem->product;
        if ($product->stock < $request->amount - $cartItem->amount) {
            return response()->json(['error' => 'Lo sentimos, hay '
                . $product->stock + $cartItem->amount
                . ' disponibles'], 422);
        }

        $product->stock -= ($request->amount - $cartItem->amount);
        $product->save();

        $cartItem->amount = $request->amount;
        $cartItem->save();

        Cache::forget('cart');
        Cache::forget('products');

        return new CartItemResource($cartItem);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        $product = $cartItem->product;
        $product->stock += $cartItem->amount;
        $product->save();

        $cartItem->delete();

        Cache::forget('cart');
        Cache::forget('products');

        return new JsonResponse(['message' => 'deleted'], 204);
    }
}
