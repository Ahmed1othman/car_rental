<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Home;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

    use DBTrait;
    public function getMainSetting(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';

        $languages = $this->getLanguagesList($language);
        $currencies = $this->getCurrenciesList($language);

        $response = [
            'main_setting'=>[
                'languages'=>$languages,
                'currencies'=>$currencies,
                'storage_base_url' => asset('storage/'),
                'dark_logo' => asset('admin/dist/logo/website_logos/logo_dark.png'),
                'light_logo' => asset('admin/dist/logo/website_logos/light.png'),
            ],
        ];

        return response()->json([
            'data' => $response,
            'status' =>'success'
        ]);

    }

    public function getFooter(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $homeData = Home::with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }])
            ->first();

        $contactData = Contact::first();

        $footerData = [
                'footer_section_paragraph'=>$homeData->footer_section_paragraph,
                'social_media'=>[
                    'whatsapp' => $contactData->whatsapp,
                    'facebook' => $contactData->facebook,
                    'twitter' => $contactData->twitter,
                    'instagram' => $contactData->instagram,
                    'snapchat' => $contactData->snapchat,
                    'linkedin'=>$contactData->linkedin,
                    'youtube'=>$contactData->youtube,
                    'tiktok'=>$contactData->tiktok,
                    ],

                'contact_data'=>[
                    'phone' => $contactData->phone,
                    'email' => $contactData->email,
                    'alternative_phone' => $contactData->alternative_phone,
                    ],
                'address_data'=>[
                    'address_line1' => $contactData->address_line1,
                    'address_line2' => $contactData->address_line2,
                    'city' => $contactData->city,
                    'state' => $contactData->state,
                    'postal_code' => $contactData->postal_code,
                    'country' => $contactData->country,
                    ],
                ];
        return response()->json(
            [
                'data' => $footerData,
               'status' =>'success'
            ]
        );
    }

}
