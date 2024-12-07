<?php

namespace App\Http\Resources;

use App\Models\Blog;
use App\Models\StaticTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedLocationResource extends JsonResource
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

        $seoQuestions = $this->seoQuestions->where('locale',$locale);
        $seoQuestionSchema = $this->jsonLD($seoQuestions);//        $car_counts = $this->getCounts($locale);
        return [
            'id' => $this->id,
            'slug' => $translation->slug,
            'name' => $translation->name,
            'description' => $translation->description,
            'content' =>  $translation->content,
//            'car_count'=>$car_counts,
            'seo_data' => [
                'seo_title' => $translation->meta_title ?? null,
                'seo_description' => $translation->meta_description ?? null,
                'seo_keywords' => $metaKeywords,
                'seo_robots' => [
                    'index'=>$translation->robots_index?? 'noindex',
                    'follow'=>$translation->robots_follow?? 'nofollow',
                ],
                'seo_image' => $this->image_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>[
                    'faq_schema'=>$seoQuestionSchema,
                ]

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
