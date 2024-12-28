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
        return [
            'id' => $this->id,
            'title' => $this->translations->first()->title??null,
            'slug' => $this->slug,
            'description' => $this->translations->first()->description??null,
            'image' => $this->file_path,
        ];
    }

}
