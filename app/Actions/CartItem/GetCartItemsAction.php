<?php

namespace App\Actions\CartItem;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GetCartItemsAction
{
    public function execute(IndexRequest $request): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');
        $activeProducts = $request->query('flag', '1');

        Cache::forget('cart');
        $paginatedCartItems = Cache::rememberForever('cart', function () use ($currentPage, $perPage, $searching, $activeProducts) {
            $query = CartItem::where('user_id', Auth::user()->id);

            if ($activeProducts === 1) {
                $query->whereIn('state', ['in_cart', 'selected', 'collected']);
            }

            $query->whereExists(function ($query) use ($searching) {
                $query->select('id')
                    ->from('products')
                    ->whereColumn('cart_items.product_id', 'products.id')
                    ->where('name', 'like', '%' . $searching . '%');
            });

            return $query->paginate($perPage, ['*'], 'page', $currentPage);
        });

        return CartItemResource::collection($paginatedCartItems);
    }
}
