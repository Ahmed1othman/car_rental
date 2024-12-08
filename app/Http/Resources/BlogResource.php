<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'slug' => $this->translations->first()->slug ??null,
            'title' => $this->translations->first()->title??null,
            'description' => $this->translations->first()->description??null,
            'content' => $this->translations->first()->content??null,
            'image' => $this->image_path,
            'created_at' => $this->created_at->format('j M, Y'),
        ];
    }


}
