<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Cache::rememberForever('products', function () {
            return Product::select('id', 'name', 'description', 'price', 'stock', 'score', 'status', 'barCode', 'image')->get();
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

        if ($request->hasFile('image')) {
            $name = uuid_create() . '.' . $request->file('image')->extension();
            $product->image = $request->file('image')->storeAs(
                'products',
                $name,
                'public'
            );
        }

        $product->save();

        Cache::forget('products');

        return new ProductResource($product);
    }
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(ProductRequest $request): ProductResource
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->score = $request->score;
        $product->barCode = $request->barCode;

        if ($request->hasFile('image')) {
            $name = uuid_create() . '.' . $request->file('image')->extension();
            $product->image = $request->file('image')->storeAs(
                'products',
                $name,
                'public'
            );
        }

        $product->save();

        Cache::forget('products');

        return new ProductResource($product);
    }
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        Cache::forget('products');

        return new JsonResponse(['message'=>'deleted'], 204);
    }
    public function changeStatus(Product $product): ProductResource
    {
        $product->status = !$product->status;
        $product->save();

        Cache::forget('products');

        return new ProductResource($product);
    }
}