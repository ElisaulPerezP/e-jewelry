<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => auth()->user()->id,
            'product_id' => $this->product->id,
            'amount' => $this->amount,
            'state' => $this->state,
            'product_image' => $this->product->image,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
        ];
    }
}
