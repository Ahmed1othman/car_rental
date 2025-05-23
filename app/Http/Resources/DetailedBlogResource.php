<?php

namespace App\Http\Resources;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\WebPageSchemaTrait;
use App\Traits\BreadcrumbSchemaTrait;
use App\Traits\BlogPostingSchemaTrait;
use App\Traits\FAQSchemaTrait;

class DetailedBlogResource extends JsonResource
{
    use OrganizationSchemaTrait, WebPageSchemaTrait, BreadcrumbSchemaTrait, BlogPostingSchemaTrait, FAQSchemaTrait;

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
        $recentlyBlog = Blog::where('is_active', true)
            ->whereNot('id', $this->id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();


        return [
            'id' => $this->id,
            'slug' => $this->slug ?? null,
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
                    //'index'=>'noindex',
                    //'follow'=>'nofollow',
                ],
                'seo_image' => $base_url."/".$this->image_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>array_filter([
                    'faq_schema'=> $this->getFAQSchema($seoQuestions),
                    'organization_schema' => $this->getOrganizationSchema(),
                    'webpage_schema' => $this->getWebPageSchema([
                        'url' => config('app.url') . "/{$locale}/blogs/{$this->slug}",
                        'name' => $translation->title ?? '',
                        'description' => $translation->meta_description ?? '',
                        'image' => asset('storage/' . $this->image_path),
                        'date_modified' => $this->updated_at->toIso8601String(),
                        'date_published' => $this->created_at->toIso8601String(),
                    ]),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => __('messages.home')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/blogs",
                            'name' => __('messages.blogs')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/blogs/{$this->slug}",
                            'name' => $translation->title ?? ''
                        ]
                    ]),
                    'blog_posting_schema' => $this->getBlogPostingSchema([
                        'url' => config('app.url') . "/{$locale}/blogs/{$this->slug}",
                        'title' => $translation->title ?? '',
                        'description' => $translation->meta_description ?? '',
                        'content' => $translation->content ?? '',
                        'image' => asset('storage/' . $this->image_path),
                        'date_modified' => $this->updated_at->toIso8601String(),
                        'date_published' => $this->created_at->toIso8601String(),
                        'keywords' => $metaKeywords ?? ''
                    ])
                ])
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
