<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{
    public function toArray($request)
    {
        $translation = $this->translations->first();

        return [
            'about_us_data' => [
                'our_mission_image_path'=> $this->our_mission_image_path,
                'why_choose_image_path'=> $this->why_choose_image_path,
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
                'meta_title' => $translation->meta_title,
                'meta_description' => $translation->meta_description,
                'meta_keywords' =>implode(', ', array_column(json_decode($translation->meta_keywords, true), 'value')),
            ],
        ];
    }
}
