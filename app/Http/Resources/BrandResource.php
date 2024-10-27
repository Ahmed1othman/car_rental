<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'slug' => $this->translations->first()->meta_title,
            'name' => $this->translations->first()->name,
            'description' => $this->translations->first()->description,
            'image' => $this->logo_path,
            'car_count'=>$this->cars->count(),
        ];
    }
}
