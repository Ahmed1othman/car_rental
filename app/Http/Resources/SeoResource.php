<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\FAQSchemaTrait;

class SeoResource extends JsonResource
{
    use OrganizationSchemaTrait, FAQSchemaTrait;
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
        $seoQuestionSchema = $this->getFAQSchema($seoQuestions);        return [
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
                    'organization_schema'=>$this->getOrganizationSchema()
                ]
            ],
        ];
    }

    // Removed the jsonLD function as it's no longer needed

}
