<?php

namespace App\Imports;

use App\Actions\imports\ProductsConciliationStockAction;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithStartRow, WithChunkReading, ShouldQueue
{
    public function model(array $row): Product
    {
        if (!(Product::find($row[0]) !== null)) {
            $newPproduct = new Product([
               'name' => $row[1],
               'description'=> $row[2],
               'price'=> $row[3],
               'stock' => $row[4],
               'score'=> $row[5],
               'status'=> $row[6],
               'barCode'=> $row[7],
               'image'=> $row[8],
            ]);
            $newPproduct->save();

            return $newPproduct;
        }

        $parentProduct = Product::find($row[0]);
        $parentProduct->name = $row[1];
        $parentProduct->description = $row[2];
        $parentProduct->price = $row[3];
        $parentProduct->stock = $row[4];
        $parentProduct->score = $row[5];
        $parentProduct->status = $row[6];
        $parentProduct->barCode = $row[7];
        $parentProduct->image = $row[8];

        if ($parentProduct->isDirty('stock')) {
            $childProduct = new Product([
                'name' => $row[1],
                'description'=> $row[2],
                'price'=> $row[3],
                'stock' => $row[4],
                'score'=> $row[5],
                'status'=> $row[6],
                'barCode'=> $row[7],
                'image'=> $row[8],
            ]);
            $childProduct->save();
            $childProduct->stock = (new ProductsConciliationStockAction())($parentProduct, $childProduct->stock);
            $childProduct->save();
            $parentProduct->stock = 0;
            $parentProduct->save();

            DB::table('table_updated_product')->insert([
                'parent_product_id' => $parentProduct->id,
                'child_product_id' => $childProduct->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $childProduct;
        }


        $parentProduct->stock = (new ProductsConciliationStockAction())($parentProduct, $row[4]);
        $parentProduct->save();

        return $parentProduct;
    }
    public function startRow(): int
    {
        return 2;
    }
    public function chunkSize(): int
    {
        return 10;
    }
}
