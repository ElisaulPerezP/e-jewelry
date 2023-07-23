<?php

namespace App\Actions\imports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DeleteProductsOutStockRegisterAction
{
    public function __invoke(Product $parentProduct): bool
    {
        DB::table('table_stock_out_product')
            ->where('product_id', $parentProduct->id)
            ->where('created_at', '<', now())
            ->delete();

        return true;
    }
}
