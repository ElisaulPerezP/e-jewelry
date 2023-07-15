<?php

namespace App\Actions\imports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsConciliationStockAction
{
    public function __invoke(Product $parentProduct, int $childProductStock): int
    {
        $totalOutStock = 0;
        $outEvents = DB::table('table_stock_out_product')
            ->where('product_id', $parentProduct->id)
            ->where('created_at', '<', now())
            ->get();

        foreach ($outEvents as $outEvent) {
            $totalOutStock += $outEvent->amount;
        }

        return $childProductStock - $totalOutStock;
    }
}
