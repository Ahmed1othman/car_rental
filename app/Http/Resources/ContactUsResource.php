<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'contact_us_title'=> $this->homeData->contact_us_title,
            'contact_us_paragraph'=> $this->homeData->contact_us_paragraph,
            'contact_us_detail_title'=> $this->hometData->contact_us_detail_title,
            'contact_us_detail_paragraph'=> $this->homeData->contact_us_title,
            'faq_section_title'=> $this->homeData->contact_us_title,
            'faq_section_paragraph'=> $this->homeData->contact_us_title,
            'faqs'=> FAQResource::collection($this->faqs),

            'website' => $this->homeData->website,
            'google_map_url' => $this->homeData->google_map_url,
            'additional_info' => $this->homeData->additional_info,

            'contact_data'=>[
                'name' => $this->contactData->name,
                'email' => $this->contactData->email,
                'phone' =>  $this->contactData->phone,
                'alternative_phone' => $this->contactData->alternative_phone,

                'address'=> [
                        'address_line1' => $this->contactData->address_line1,
                        'address_line2' => $this->contactData->address_line2,
                        'city' => $this->contactData->city,
                        'state' => $this->contactData->state,
                        'postal_code' => $this->contactData->postal_code,
                        'country' => $this->contactData->country,
                    ],

                'social_media_links'=>[
                    'facebook'=>$this->contactData->facebook,
                    'twitter'=>$this->contactData->twitter,
                    'linkedin'=>$this->contactData->linkedin,
                    'instagram'=>$this->contactData->instagram,
                    'youtube'=>$this->contactData->youtube,
                    'whatsapp'=>$this->contactData->whatsapp,
                    'tiktok'=>$this->contactData->tiktok,
                   'snapchat'=>$this->contactData->snapchat,

                ],
                'contact_us_title'=> $this->contactData->contact_us_title,
            ],


        ];
    }
}
