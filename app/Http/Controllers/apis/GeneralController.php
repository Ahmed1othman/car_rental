<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvancedSearchSettingResource;
use App\Http\Resources\StaticTranslationsResource;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Color;
use App\Models\Contact;
use App\Models\Gear_type;
use App\Models\Home;
use App\Models\StaticTranslation;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class GeneralController extends Controller
{

    use DBTrait;
    public function getMainSetting(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';

        $languages = $this->getLanguagesList($language);
        $currencies = $this->getCurrenciesList($language);
        $translations_data = StaticTranslation::where('locale', $language)->get();
        $translationsData = $translations_data->pluck('value', 'key');
        $contact = Contact::first();
        $response = [
            'main_setting'=>[
                'languages'=>$languages,
                'currencies'=>$currencies,
                'translation_data'=> $translationsData,
                'storage_base_url' => asset('storage/'),
                'dark_logo' => asset('admin/dist/logo/website_logos/logo_dark.svg'),
                'light_logo' => asset('admin/dist/logo/website_logos/logo_light.svg'),
                'contact_data'=>[
                    'phone' => $contact->phone,
                    'whatsapp' => $contact->whatsapp,
                    ],
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


    public function advancedSearchSetting(){
        $brands = Brand::all();
        $categories = Category::all();
        $colors = Color::all();
        $carGears = Gear_type::all();

        $data = new Fluent([
            'brands' => $brands,
            'categories' => $categories,
            'colors' => $colors,
            'gear_types' => $carGears,
        ]);

        return new AdvancedSearchSettingResource($data);
    }
}
