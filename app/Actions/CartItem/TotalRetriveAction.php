<?php

namespace App\Actions\CartItem;

use App\Models\CartItem;
use Illuminate\Http\JsonResponse;

class TotalRetriveAction
{
    public function execute(): JsonResponse
    {
        $total = 0;
        $cartItems = CartItem::where('state', 'selected')
            ->where('user_id', auth()->user()->id)
            ->get();

        foreach ($cartItems as $cartItem) {
            $total += $cartItem->amount * $cartItem->product->price;
        }

        return response()->json(['total' => $total]);
    }
}
