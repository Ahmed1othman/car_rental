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
            'seo_description' => $this->id,
            'seo_image' => $this->translations->first()->name,
            'seo_alt' => $this->translations->first()->description,
            'seo_robots' => $this->image_path,
            'seo_titles' => $this->image_path,
        ];
    }
}
