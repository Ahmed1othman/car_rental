<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FAQResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->translations->first()->question ?? null,
            'slug' => $this->slug,
            'answer' => $this->translations->first()->answer ?? null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
