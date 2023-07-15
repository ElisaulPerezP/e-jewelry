<?php

namespace App\Actions\CartItem;

use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DeleteCartItemAction
{
    public function __invoke(CartItem $cartItem): JsonResponse
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
