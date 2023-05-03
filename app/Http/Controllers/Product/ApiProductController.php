<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Cache::rememberForever('products', function () {
            return Product::select('id', 'name', 'description', 'price', 'stock', 'score', 'status', 'barCode')->get();
        });
        return ProductResource::collection($products);
    }

    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->score = $request->score;
        $product->barCode = $request->barCode;

        $product->save();

        Cache::forget('products');

        return new ProductResource($product);
    }
}
