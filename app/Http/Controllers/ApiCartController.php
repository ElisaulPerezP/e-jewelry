<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemCart\AmountItemCartRequest;
use App\Http\Requests\ItemCart\StateItemCartRequest;
use App\Http\Resources\ItemCartResource;
use App\Models\ItemCart;
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
            return auth()->user()->itemsCart->where('state', 'selected', 'in_cart');
        });

        return ItemCartResource::collection($itemsCart);
    }

    public function store(Product $product): ItemCartResource
    {
        $itemCart = new ItemCart();
        $itemCart->user_id = auth()->user()->id;
        $itemCart->product_id = $product->id;
        $itemCart->amount = 1;
        $itemCart->state = 'in_cart';
        $itemCart->save();

        $product->stock--;
        $product->save();

        Cache::forget('cart');
        Cache::forget('products');

        return new ItemCartResource($itemCart);
    }

    public function changeState(StateItemCartRequest $request, ItemCart $itemCart): ItemCartResource
    {
        $itemCart->state = $request->state;
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }

    public function setAmount(AmountItemCartRequest $request, ItemCart $itemCart): ItemCartResource|JsonResponse
    {
        $product = $itemCart->product;
        if ($product->stock < $request->amount - $itemCart->amount) {
            return response()->json(['error' => 'Lo sentimos, hay '
                . $product->stock + $itemCart->amount
                . ' disponibles'], 422);
        }

        $product->stock -= ($request->amount - $itemCart->amount);
        $product->save();

        $itemCart->amount = $request->amount;
        $itemCart->save();

        Cache::forget('cart');
        Cache::forget('products');

        return new ItemCartResource($itemCart);
    }

    public function destroy(ItemCart $itemCart): JsonResponse
    {
        $product = $itemCart->product;
        $product->stock += $itemCart->amount;
        $product->save();

        $itemCart->delete();

        Cache::forget('cart');
        Cache::forget('products');

        return new JsonResponse(['message' => 'deleted'], 204);
    }
}
