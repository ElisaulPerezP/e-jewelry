<?php

namespace App\Actions\CartItem;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AddCartItemAction
{
    public function execute(Product $product): CartItemResource
    {
        $cartItem = new CartItem();
        $cartItem->user_id = Auth::user()->id;
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
}
