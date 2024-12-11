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
        $base_url = asset('storage/');
        // Retrieve translation for the requested locale or fallback
        $locale = app()->getLocale() ?? 'en';
        $translation = $this->translations->where('locale', $locale)->first();

        // Format the created_at date
        $formattedCreatedAt = $this->created_at ? $this->created_at->format('j M, Y') : null;

        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;

        $seoQuestions = $this->seoQuestions->where('locale',$locale);
        $seoQuestionSchema = $this->jsonLD($seoQuestions);
        $recentlyBlog = Blog::where('is_active', true)
            ->whereNot('id', $this->id)
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
                'seo_title' => $translation->meta_title ?? null,
                'seo_description' => $translation->meta_description ?? null,
                'seo_keywords' => $metaKeywords,
                'seo_robots' => [
                    'index'=>$translation->robots_index?? 'noindex',
                    'follow'=>$translation->robots_follow?? 'nofollow',
                ],
                'seo_image' => asset('admin/dist/logo/website_logos/logo_dark.svg')?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>[
                    'faq_schema'=>$seoQuestionSchema,
                ]
            ],

        ];
    }


    public function jsonLD($seoQuestions)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $seoQuestions->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question_text,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer_text,
                    ],
                ];
            }),
        ];

    }
}
