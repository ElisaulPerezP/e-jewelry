<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @return array<string, array<string>>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'total' => $this->total,
            'currency' => $this->currency,
            'state' => $this->state,
            'return_url' => $this->return_url,
            'process_url' => $this->process_url,
            'created_at' => $this->created_at,
            'cart_items' => CartItemResource::collection($this->cartItems),
        ];
    }
}
