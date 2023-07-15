<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class DeleteProductAction
{
    public function __invoke(Product $product): void
    {
        $product->delete();
        Cache::forget('products');
    }
}
