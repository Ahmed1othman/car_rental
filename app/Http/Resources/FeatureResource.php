<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
            'icon_class' => $this->icon->icon_class
        ];
    }
}
