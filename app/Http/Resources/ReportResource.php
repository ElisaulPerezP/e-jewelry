<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * @return array<string, array<string>>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'filePath' => $this->file_path,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ];
    }
}
