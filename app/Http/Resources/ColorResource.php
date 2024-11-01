<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */



    public function toArray(Request $request): array
    {
        $language = app()->getLocale();
        $translations = $this->translations->where('locale',$language)->first();
        return [
            'id' => $this->id,
            'slug' => $translations->slug,
            'name' => $translations->name,
            'color_code' => $this->color_code,
            'car_count'=>$this->cars->count(),
        ];
    }
}
