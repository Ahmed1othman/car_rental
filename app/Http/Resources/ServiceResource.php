<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->translations->first()->name ?? null,
            'slug' => $this->slug,
            'description' => $this->translations->first()->description ?? null,
            'image_path' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
