<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ChangeProductStatusAction
{
    public function execute(Product $product): void
    {
        $product->status = !$product->status;
        $product->save();

        Cache::forget('products');
    }
}
