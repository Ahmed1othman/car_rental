<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->translations->first()->title,
            'description' => $this->translations->first()->description,
            'mobile_image' => $this->mobile_image_path,
            'web_image' => $this->web_image_path,
            'position_key' => $this->advertisementPosition->position_key
        ];
    }
}
