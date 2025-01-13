<?php

namespace App\Http\Resources;

use App\Models\Blog;
use App\Models\StaticTranslation;
use App\Traits\BreadcrumbSchemaTrait;
use App\Traits\FAQSchemaTrait;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\WebPageSchemaTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedLocationResource extends JsonResource
{
    use OrganizationSchemaTrait, WebPageSchemaTrait, BreadcrumbSchemaTrait, FAQSchemaTrait;

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
        $seoQuestionSchema = $this->jsonLD($seoQuestions);//        $car_counts = $this->getCounts($locale);
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $translation->name,
            'description' => $translation->description,
            'article' => $translation->article,
            'section_title' => $translation->title,
            'content' =>  $translation->content,
            'seo_data' => [
                'seo_title' => $translation->meta_title ?? null,
                'seo_description' => $translation->meta_description ?? null,
                'seo_keywords' => $metaKeywords,
                'seo_robots' => [
                    'index'=>$translation->robots_index?? 'noindex',
                    'follow'=>$translation->robots_follow?? 'nofollow',
                ],
                'seo_image' => $base_url.$this->image_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>array_filter([
                    'faq_schema'=> $this->getFAQSchema($seoQuestions),
                    'organization_schema' => $this->getOrganizationSchema(),
                    'local_business_schema' => $this->getLocalBusinessSchema(),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => __('messages.home')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/product/filter",
                            'name' => __('messages.cars')
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
                ]),
            ],
        ];
    }

    public function getCounts(string $language): string
    {
        $car = StaticTranslation::where('locale', $language)->where('key', 'car')->first();
        $cars = StaticTranslation::where('locale', $language)->where('key', 'cars')->first();
        $count = $this->cars->count();
        if ($language == 'ar'){
            if ($count > 2 && $count <10)
                $car_counts = $count . " " . $cars->value;
            else if ($count == 2)
                $car_counts = "سيارتان";
            else
                $car_counts = $count. " ". $car->value;
        } else{
            if ($count <= 1)
                $car_counts = $count . " " . $car->value;
            else
                $car_counts = $count . " " . $cars->value;
        }

        return $car_counts;
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
