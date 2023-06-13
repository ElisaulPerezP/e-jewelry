<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        Cache::forget('products');
        $searching = $request->query('searching', '');
        $perPage = $request->query('per_page', 6);
        $currentPage = $request->query('current_page', 1);
        $activeProducts = $request->query('active_products', 1);
        $activeProducts ?
        $paginatedProducts = Cache::rememberForever('products', function () use ($currentPage, $perPage, $searching) {
            return Product::where('status', 1)->where('name', 'like', '%' . $searching . '%')->paginate($perPage, ['id', 'name', 'description', 'price', 'stock', 'score', 'status', 'barCode', 'image'], 'page', $currentPage);
        }) :
            $paginatedProducts = Cache::rememberForever('products', function () use ($currentPage, $perPage, $searching) {
                return Product::where('name', 'like', '%' . $searching . '%')->paginate($perPage, ['id', 'name', 'description', 'price', 'stock', 'score', 'status', 'barCode', 'image'], 'page', $currentPage);
            });

        return ProductResource::collection($paginatedProducts);
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
        $product->status = $request->status;
        $product->score = $request->score;
        $product->barCode = $request->barCode;
        if ($request->hasFile('image')) {
            $name = uuid_create() . '.' . $request->file('image')->extension();
            $product->image = $request->file('image')->storeAs(
                'products',
                $name,
                'public'
            );
        } else {
            $product->image = 'products/muestra.png';
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
