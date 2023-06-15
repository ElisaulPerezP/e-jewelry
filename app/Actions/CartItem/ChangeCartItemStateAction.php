<?php
namespace App\Actions\CartItem;

use App\Http\Requests\CartItem\StateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Support\Facades\Cache;

class ChangeCartItemStateAction
{
    public function execute(StateCartItemRequest $request, CartItem $cartItem): CartItemResource
    {
        $cartItem->state = $request->state;
        $cartItem->save();

        Cache::forget('cart');

        return new CartItemResource($cartItem);
    }
}
