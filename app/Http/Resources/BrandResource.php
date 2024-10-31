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
        $locale = app()->getLocale()??"en";
        $translations = $this->translations->where('locale',$locale)->first();
        return [
            'id' => $this->id,
            'slug' => $translations->slug,
            'name' => $translations->name,
            'description' => $translations->description,
            'image' => $this->logo_path,
            'car_count'=>$this->cars->count(),
        ];
    }
}
