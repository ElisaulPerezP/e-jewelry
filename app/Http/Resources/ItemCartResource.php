<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'itemCart_id' => $this->id,
            'user_id' => auth()->user()->id,
            'product_id' => $this->product_id,
            'amount' => $this->amount,
            'item_state' => $this->item_state,
            'product_image' => $this->product->image,
            'product_name' => $this->product->name,
            'products_price' => $this->product->price,
            'expire_date'=> $this->expire_date,
        ];
    }
}
