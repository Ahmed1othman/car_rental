<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\WebPageSchemaTrait;
use App\Traits\BreadcrumbSchemaTrait;
use App\Traits\FAQSchemaTrait;

class AboutUsResource extends JsonResource
{
    use OrganizationSchemaTrait, WebPageSchemaTrait, BreadcrumbSchemaTrait, FAQSchemaTrait;

    public function toArray($request)
    {
        $base_url = asset('storage/');
        $locale = app()->getLocale() ?? 'en';
        $translation = $this->translations->where('locale', $locale)->first();

        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;

        // Get SEO Questions for FAQ Schema
        $seoQuestions = $this->seoQuestions->where('locale', $locale);

        return [
            'about_us_data' => [
                'our_mission_image_path'=> $base_url .'/'. $this->our_mission_image_path,
                'why_choose_image_path'=> $base_url .'/'. $this->why_choose_image_path,
                'about_main_header_title' => $translation->about_main_header_title,
                'about_main_header_paragraph' => $translation->about_main_header_paragraph,
                'about_our_agency_title' => $translation->about_our_agency_title,
                'why_choose_title' => $translation->why_choose_title,
                'our_vision_title' => $translation->our_vision_title,
                'our_mission_title' => $translation->our_mission_title,
                'why_choose_content' => $translation->about_main_header_paragraph,
                'our_vision_content' => $translation->our_vision_content,
                'our_mission_content' => $translation->our_mission_content,
            ],
            'seo_data' => [
                'meta_title' => $translation->meta_title ?? null,
                'meta_description' => $translation->meta_description ?? null,
                'meta_keywords' => $metaKeywords,
                'seo_image' => $base_url ."/". $this->why_choose_image_path ?? null,
                'seo_image_alt' => $translation->meta_title ?? null,
                'schemas' => array_filter([
                    'faq_schema' => $this->getFAQSchema($seoQuestions),
                    'organization_schema' => $this->getOrganizationSchema(),
                    'webpage_schema' => $this->getWebPageSchema([
                        'url' => config('app.url') . "/{$locale}/about-us",
                        'name' => $translation->meta_title ?? 'About Us',
                        'description' => $translation->meta_description ?? '',
                        'image' => $base_url . $this->why_choose_image_path ?? null,
                    ]),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => __('messages.home')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/about-us",
                            'name' => $translation->meta_title ?? __('messages.about_us')
                        ]
                    ])
                ])
            ],
        ];
    }
}
