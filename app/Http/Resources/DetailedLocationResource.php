<?php

namespace App\Http\Resources;

use App\Traits\BreadcrumbSchemaTrait;
use App\Traits\FAQSchemaTrait;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\WebPageSchemaTrait;
use App\Traits\BlogPostingSchemaTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedLocationResource extends JsonResource
{
    use FAQSchemaTrait, OrganizationSchemaTrait, WebPageSchemaTrait, BreadcrumbSchemaTrait, BlogPostingSchemaTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale()?? "en";
        $translation = $this->translations->where('locale',$locale)->first();
        $base_url = config('app.url');
        
        $metaKeywords = $translation->meta_keywords ? explode(',', $translation->meta_keywords) : [];

        $seoQuestions = collect($this->seo_questions)->where('locale', $locale);

        return [
            'id' => $this->id,
            'name' => $translation->name??null,
            'slug' => $this->slug,
            'description' => $translation->description??null,
            'content' => $translation->content??null,
            'image_path' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'seo_data' => [
                'meta_title' => $translation->meta_title??null,
                'meta_description' => $translation->meta_description??null,
                'meta_keywords' => $metaKeywords,
                'seo_image' => $base_url."/". $this->image_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>array_filter([
                    'faq_schema'=> $this->getFAQSchema($seoQuestions),
                    'organization_schema' => $this->getOrganizationSchema(),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => "home page"
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/product/filter",
                            'name' => "search car rental in dubai" 
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/product/location/{$this->slug}",
                            'name' => $translation->name
                        ]
                    ]),
                    'webpage_schema' => $this->getWebPageSchema([
                        'url' => config('app.url') . "/{$locale}/product/location/{$this->slug}",
                        'name' => $translation->name,
                        'description' => $translation->meta_description,
                        'image' => asset('storage/' . $this->image_path),
                        'date_modified' => $this->updated_at->toIso8601String(),
                        'date_published' => $this->created_at->toIso8601String(),
                    ]),
                    'blog_posting_schema' => $this->getBlogPostingSchema([
                        'url' => config('app.url') . "/{$locale}/product/location/{$this->slug}",
                        'title' => $translation->title ?? '',
                        'description' => $translation->description ?? '',
                        'content' => $translation->article ?? '',
                        'image' => asset('storage/' . $this->image_path),
                        'date_modified' => $this->updated_at->toIso8601String(),
                        'date_published' => $this->created_at->toIso8601String(),
                        'keywords' => $metaKeywords ?? ''
                    ])
                ]),
            ],
        ];
    }
}
