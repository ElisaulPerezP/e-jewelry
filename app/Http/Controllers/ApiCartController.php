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

    public function updateItemStateToSaved(ItemCart $itemCart): ItemCartResource|string
    {
        $itemCart->item_state = 'saved';
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }

    public function updateItemStateToInCart(ItemCart $itemCart): ItemCartResource|string
    {
        $itemCart->item_state = 'in_cart';
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }
    public function updateItemStateToInOrder(ItemCart $itemCart): ItemCartResource|string
    {
        $itemCart->item_state = 'in_order';
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }
    public function updateExpireDate(ItemCart $itemCart): ItemCartResource|string
    {
        $now = time();
        $expireUnixDate = $now + 3600 * 2;
        $itemCart->expire_date = $expireUnixDate;
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }

    public function resetItemAmount(ItemCart $itemCart): ItemCartResource|JsonResponse
    {
        $product = Product::find($itemCart->product->id);
        $product->stock += $itemCart->amount;
        $product->save();

        $itemCart->amount = 0;
        $itemCart->save();

        Cache::forget('cart');

        return new ItemCartResource($itemCart);
    }
    public function updateAmount(ItemCartRequest $request, ItemCart $itemCart): ItemCartResource|JsonResponse
    {
        if ($itemCart->product()->first()->stock < $request->amount - $itemCart->amount) {
            return response()->json(['error' => 'Lo sentimos, hay '
                . $itemCart->product()->first()->stock + $itemCart->amount
                . ' disponibles'], 422);
        }

        $product = Product::find($itemCart->product->id);
        $product->stock -= ($request->amount - $itemCart->amount);
        $product->save();

        $itemCart->amount = $request->amount;
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
        $itemCart = ItemCart::where('product_id', $product->id)->where('user_id', auth()->user()->id)->first();

        if (is_null($itemCart)) {
            $now = time();
            $expireUnixDate = $now + 3600 * 2;
            $itemCart = new ItemCart();
            $itemCart->user_id = auth()->user()->id;
            $itemCart->product_id = $product->id;
            $itemCart->amount = 1;
            $itemCart->item_state = 'in_cart';
            $itemCart->expire_date = $expireUnixDate;

            $itemCart->save();
            Cache::forget('cart');
        }

        return new ItemCartResource($itemCart);
    }

    public function destroy(ItemCart $itemCart): JsonResponse
    {
        $itemCart->delete();
        Cache::forget('cart');

        return new JsonResponse(['message' => 'deleted'], 204);
    }
}
