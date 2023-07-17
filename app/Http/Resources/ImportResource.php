<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
{
    /**
     * @return array<string, array<string>>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'comments' => $this->comments,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'file' => $this->file,
            ];
    }
}
