<?php

namespace App\Http\Controllers;

use App\Http\Requests\cart\ItemCartRequest;
use App\Http\Resources\ItemCartResource;
use App\Http\Resources\ProductResource;
use App\Models\ItemCart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiCartController extends Controller
{
    public function index(User $user): AnonymousResourceCollection
    {
        $itemCarts = Cache::rememberForever('cart', function () use ($user) {
            return $user->itemCarts()->get();
        });

        return ItemCartResource::collection($itemCarts);
    }

    public function update(ItemCartRequest $request, ItemCart $itemCart): ItemCartResource|string
    {
        if ($itemCart->product()->first()->stock < $request->amount - $itemCart->amount) {
            return response(['error' => 'No hay suficiente stock disponible'], 422);
        }

        $product = Product::find($itemCart->product->id);
        $product->stock -= ($request->amount - $itemCart->amount);
        $product->save();

        $itemCart->user_id = $request->user_id;
        $itemCart->product_id = $request->product_id;
        $itemCart->amount = $request->amount;
        $itemCart->item_state = $request->item_state;
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(Product $product): ItemCartResource|string
    {

        $itemCart = ItemCart::where('product_id', $product->id)->first();

        if (is_null($itemCart)) {
            $itemCart = new ItemCart();
            $itemCart->user_id = auth()->id();
            $itemCart->product_id = $product->id;
            $itemCart->amount = 1;
            $itemCart->item_state = 'in_cart';

            $itemCart->save();
        }

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }

    public function destroy(ItemCart $itemCart): JsonResponse
    {
        $itemCart->delete();
        Cache::forget('cart');

        return new JsonResponse(['message' => 'deleted'], 204);
    }
}
