<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * @return array<string, array<string>>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product->id,
            'amount' => $this->amount,
            'state' => $this->state,
            'product_image' => $this->product->image,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
        ];
    }
}
