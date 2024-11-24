<?php

namespace App\Http\Resources;

use App\Models\StaticTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale()?? "en";
        $translations = $this->translations->where('locale',$locale)->first();

        return [
            'id' => $this->id,
            'slug' => $translations->slug??null,
            'title' => $translations->title??null,
            'description' => $translations->description??null,
            'image' => $this->file_path,
        ];
    }

}
