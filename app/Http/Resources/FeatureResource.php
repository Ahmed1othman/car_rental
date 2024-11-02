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
        $translations = $this->translations->where('locale', $language)->first();

        return [
            'id' => $this->id,
            'slug' => $translations->slug ?? null, // Safely handle null if translation is not available
            'name' => $translations->name ?? null, // Safely handle null if translation is not available
            'icon_class' => $this->icon->icon_class ?? null, // Safely handle null if icon or icon_class is not available
        ];
    }
}
