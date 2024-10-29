<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    public function toArray($request)
    {
        $contactData = $this->contactData;
        $homeData = $this->homeData->translations->first();
        $faqs = $this->faqs;

//
//        dd($homeData);
        return [
            'contact_us_title' => $homeData->contact_us_title ?? null,
            'contact_us_paragraph' => $homeData->contact_us_paragraph ?? null,
            'contact_us_detail_title' => $homeData->contact_us_detail_title ?? null,
            'contact_us_detail_paragraph' => $homeData->contact_us_detail_paragraph ?? null, // Fixed line
            'faq_section_title' => $homeData->faq_section_title ?? null,
            'faq_section_paragraph' => $homeData->faq_section_paragraph ?? null,
            'faqs' => FAQResource::collection($faqs),

            'website' => $homeData->website ?? null,
            'google_map_url' => $contactData->google_map_url ?? null,
            'additional_info' => $contactData->additional_info ?? null,

            'contact_data' => [
                'name' => $contactData->name ?? null,
                'email' => $contactData->email ?? null,
                'phone' => $contactData->phone ?? null,
                'alternative_phone' => $contactData->alternative_phone ?? null,
                'address' => [
                    'address_line1' => $contactData->address_line1 ?? null,
                    'address_line2' => $contactData->address_line2 ?? null,
                    'city' => $contactData->city ?? null,
                    'state' => $contactData->state ?? null,
                    'postal_code' => $contactData->postal_code ?? null,
                    'country' => $contactData->country ?? null,
                ],
                'social_media_links' => [
                    'facebook' => $contactData->facebook ?? null,
                    'twitter' => $contactData->twitter ?? null,
                    'linkedin' => $contactData->linkedin ?? null,
                    'instagram' => $contactData->instagram ?? null,
                    'youtube' => $contactData->youtube ?? null,
                    'whatsapp' => $contactData->whatsapp ?? null,
                    'tiktok' => $contactData->tiktok ?? null,
                    'snapchat' => $contactData->snapchat ?? null,
                ],
            ],
        ];
    }
}
