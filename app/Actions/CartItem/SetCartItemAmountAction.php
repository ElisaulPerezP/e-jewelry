<?php

namespace App\Actions\CartItem;

use App\Http\Requests\CartItem\AmountCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SetCartItemAmountAction
{
    public function execute(AmountCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        $product = $cartItem->product;
        $realCartItemStock = $cartItem->state === 'collected' ? 0 : $cartItem->amount;
        if ($product->stock < $request->amount - $realCartItemStock) {
            return response()->json(['error' => 'Lo sentimos, hay ' . ($product->stock + $realCartItemStock) . ' disponibles'], 422);
        }

        $product->stock -= ($request->amount - $cartItem->amount);
        $product->save();

        $cartItem->amount = $request->amount;
        $cartItem->save();

        Cache::forget('cart');
        Cache::forget('products');

        return new CartItemResource($cartItem);
    }
}
