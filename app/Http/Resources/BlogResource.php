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
            'title' => $this->translations->first()->title??null,
            'slug' => $this->slug,
            'image_path' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'created_at' => $this->created_at ? $this->created_at->format('j M, Y') : null
        ];
    }
}
