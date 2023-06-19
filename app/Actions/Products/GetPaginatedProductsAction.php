<?php

namespace App\Actions\Products;

use App\Http\Requests\IndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class GetPaginatedProductsAction
{
    public function execute(IndexRequest $request): AnonymousResourceCollection
    {
        Cache::forget('products');
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', 6);
        $currentPage = $request->query('current_page', 1);
        $activeProducts = $request->query('flag', 1);

        $paginatedProducts = Cache::rememberForever('products', function () use ($currentPage, $perPage, $searching, $activeProducts) {
            $query = Product::query();

            if ($activeProducts) {
                $query->where('status', 1);
            }

            $query->where('name', 'like', '%' . $searching . '%');

            return $query->paginate($perPage, ['id', 'name', 'description', 'price', 'stock', 'score', 'status', 'barCode', 'image'], 'page', $currentPage);
        });

        return ProductResource::collection($paginatedProducts);
    }
}
