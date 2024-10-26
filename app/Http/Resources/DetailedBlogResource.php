<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedBlogResource extends JsonResource
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
            'title' => $this->translations->first()->title,
            'description' => $this->translations->first()->description,
            'content' => $this->translations->first()->content,
            'image' => $this->image_path,
            'related_cars'=> $this->cars,
            'created_at' => $this->created_at->format('j M, Y'),
        ];
    }


}
