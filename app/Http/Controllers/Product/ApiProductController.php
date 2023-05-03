<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ApiProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Cache::rememberForever('products', function () {
            return Product::select('id', 'name', 'description', 'price', 'subcategory', 'stock', 'score', 'status', 'barCode')->get();
        });
        return ProductResource::collection($products);
    }
}
