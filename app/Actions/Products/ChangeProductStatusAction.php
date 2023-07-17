<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ChangeProductStatusAction
{
    public function __invoke(Product $product): void
    {
        $product->status = !$product->status;
        $product->save();

        Cache::forget('products');
    }
}
