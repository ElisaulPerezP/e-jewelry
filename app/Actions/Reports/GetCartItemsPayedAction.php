<?php

namespace App\Actions\Reports;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetCartItemsPayedAction
{
    public function __invoke(IndexRequest $request): AnonymousResourceCollection
    {
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', '6');
        $currentPage = $request->query('current_page', '1');

        Cache::forget('cartPayed');
        $paginatedCartItems = Cache::rememberForever('cartPayed', function () use ($currentPage, $perPage, $searching) {
            $query = CartItem::where('state', 'paid');

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
