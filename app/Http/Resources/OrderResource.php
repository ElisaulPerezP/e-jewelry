<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_reference' => $this->payment_reference,
            'description' => $this->description,
            'total' => $this->total,
            'currency' => $this->currency,
            'order_state' => $this->order_state,
            'expiration' => $this->expiration,
            'return_url' => $this->return_url,
            'process_url' => $this->process_url,
            'created_at' => $this->created_at,
        ];
    }
}
