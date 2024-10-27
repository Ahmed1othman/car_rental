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
            'slug' => $this->translations->first()->meta_title,
            'title' => $this->translations->first()->title,
            'description' => $this->translations->first()->description,
            'content' => $this->translations->first()->content,
            'image' => $this->image_path,
            'created_at' => $this->created_at->format('j M, Y'),
            'related_cars'=>CarResource::collection($this->cars),

            'seo_data'=>[
                'meta_title' => $this->translations->first()->meta_title,
                'meta_description' => $this->translations->first()->meta_description,
                'meta_keywords' => $this->translations->first()->meta_keywords,
            ]
        ];
    }


}
