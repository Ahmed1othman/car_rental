<?php

namespace App\Http\Resources;

use App\Models\Blog;
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
        // Retrieve translation for the requested locale or fallback
        $locale = app()->getLocale() ?? 'en';
        $translation = $this->translations->where('locale', $locale)->first();

        // Format the created_at date
        $formattedCreatedAt = $this->created_at ? $this->created_at->format('j M, Y') : null;

        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;


        $recentlyBlog = Blog::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();


        return [
            'id' => $this->id,
            'slug' => $translation->slug ?? null,
            'title' => $translation->title ?? null,
            'description' => $translation->description ?? null,
            'content' => $translation->content ?? null,
            'image' => $this->image_path,
            'created_at' => $formattedCreatedAt,
            'related_cars' => CarResource::collection($this->cars),
            'related_blogs' => BlogResource::collection($recentlyBlog),
            'seo_data' => [
                'meta_title' => $translation->meta_title ?? null,
                'meta_description' => $translation->meta_description ?? null,
                'meta_keywords' => $metaKeywords,
            ],
        ];
    }
}
