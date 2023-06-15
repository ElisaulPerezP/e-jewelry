<?php

namespace App\Actions\CartItem;

use App\Http\Resources\CartItemResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GetCartItemsAction
{
    public function execute(): AnonymousResourceCollection
    {
        Cache::forget('cart');
        $itemsCart = Cache::rememberForever('cart', function () {
            return Auth::user()->cartItems->whereIn('state', ['selected', 'in_cart']);
        });

        return CartItemResource::collection($itemsCart);
    }
}
