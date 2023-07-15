<?php

namespace App\Actions\CartItem;

use App\Http\Requests\CartItem\AmountCartItemRequest;
use App\Http\Requests\CartItem\StateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ChangeCartItemStateAction
{
    public function __invoke(StateCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        if ($cartItem->state !== 'collected') {
            $cartItem->state = $request->state;
            $cartItem->save();
            Cache::forget('cart');

            return new CartItemResource($cartItem);
        }
        $response = (new SetCartItemAmountAction())(new AmountCartItemRequest(['amount' => $cartItem->amount]), $cartItem);

        if ($response instanceof CartItemResource) {
            $cartItem->state = $request->state;
            $cartItem->save();
            Cache::forget('cart');

            return new CartItemResource($cartItem);
        }

        return $response;
    }
}
