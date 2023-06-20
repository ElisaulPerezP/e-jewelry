<?php

namespace App\Actions\Products;

use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class UpdateProductAction
{
    public function execute(ProductRequest $request, Product $product): ProductResource
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
}
