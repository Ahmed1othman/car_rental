<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
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
        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;

        $seoQuestions = $this->seoQuestions->where('locale',$locale);
        $seoQuestionSchema = $this->jsonLD($seoQuestions);        return [
            'seo_data' => [
                'seo_title' => $translation->meta_title ?? null,
                'seo_description' => $translation->meta_description ?? null,
                'seo_keywords' => $metaKeywords,
                'seo_robots' => [
                    'index'=>$translation->robots_index?? 'noindex',
                    'follow'=>$translation->robots_follow?? 'nofollow',
                ],
                'seo_image' => $base_url.$this->logo_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,

                'schemas'=>[
                    'faq_schema'=>$seoQuestionSchema,
                    'organization_schema'=>$this->getOrganizationSchema()
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



    public function getOrganizationSchema()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "CarRental",
            "name" => "Afandina Car Rental",
            "url" => config('app.url'),
            "logo" => asset('images/logo.png'),
            "image" => asset('images/office.jpg'),
            "description" => "Afandina Car Rental offers a wide range of vehicles for rent in Dubai, ensuring comfort, convenience, and competitive pricing.",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "123 Sheikh Zayed Road",
                "addressLocality" => "Dubai",
                "addressRegion" => "Dubai",
                "postalCode" => "00000",
                "addressCountry" => "UAE"
            ],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => "+971-50-123-4567",
                "contactType" => "Customer Service",
                "areaServed" => "Dubai, UAE",
                "availableLanguage" => ["English", "Arabic"]
            ],
            "sameAs" => [
                "https://www.facebook.com/AfandinaCarRental",
                "https://www.instagram.com/AfandinaCarRental"
            ]
        ];
    }
}
