<?php

namespace App\Http\Resources;

use App\Traits\OrganizationSchemaTrait;
use App\Traits\BreadcrumbSchemaTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    use OrganizationSchemaTrait, BreadcrumbSchemaTrait;

    public function toArray($request)
    {
        $homeData = $this->resource['homeData']->translations->first();
        $contactData = $this->resource['contactData'];

        $locale = app()->getLocale() ?? 'en';

        return [
            'header_section' => [
                'hero_header_title' => $homeData->hero_header_title,
                'hero_header_video_path' => $this->resource['homeData']->hero_header_video_path ? asset('storage/' . $this->resource['homeData']->hero_header_video_path) : null,
                'hero_header_image_path' => $this->resource['homeData']->hero_header_image_path ? asset('storage/' . $this->resource['homeData']->hero_header_image_path) : null,
                'hero_media_type' => $this->resource['homeData']->hero_type,
                'social_media_links' => [
                    'facebook' => $contactData->facebook,
                    'twitter' => $contactData->twitter,
                    'instagram' => $contactData->instagram,
                    'snapchat' => $contactData->snapchat,
                ],
                'menu_keys' => [],
            ],
            'only_on_afandina_section' => [
                'car_only_section_title' => $homeData->car_only_section_title,
                'car_only_section_paragraph' => $homeData->car_only_section_paragraph,
                'only_on_afandina' => $this->resource['onlyOnAfandina'],
            ],
            'advertisements' => [
                'advertisements' => AdvertisementResource::collection($this->resource['advertisements']),
            ],
            'special_offers_section' => [
                'special_offers_title' => $homeData->special_offers_section_title,
                'special_offers_section_paragraph' => $homeData->special_offers_section_paragraph,
                'special_offers' => $this->resource['specialOffers'],
            ],
            'why_choose_us_section' => [
                'why_choose_us_title' => $homeData->why_choose_us_section_title,
                'why_choose_us_section_paragraph' => $homeData->why_choose_us_section_paragraph,
                'services' => $this->resource['services'],
            ],
            'where_find_us' => [
                'where_find_us_section_title' => $homeData->where_find_us_section_title,
                'where_find_us_section_paragraph' => $homeData->where_find_us_section_paragraph,
                'locations' => $this->resource['locations'],
            ],
            'document_section' => [
                'document_title' => $homeData->required_documents_section_title,
                'document_section_paragraph' => $homeData->required_documents_section_paragraph,
                'documents' => $this->resource['documents'],
            ],
            'short_videos_section' => [
                'short_videos_title' => "Instagram Videos",
                'short_videos' => ShortVideoResource::collection($this->resource['shortVideos']),
            ],
            'seo_data' => [
                'schemas' => array_filter([
                    'organization_schema' => $this->getOrganizationSchema(),
                    'local_business_schema' => $this->getLocalBusinessSchema(),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => __('messages.home')
                        ]
                    ]),
                ])
            ],
        ];
    }
}
