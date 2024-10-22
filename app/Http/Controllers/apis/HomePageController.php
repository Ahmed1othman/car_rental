<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Home;
use App\Models\Service;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $brands = $this->getBrandsList($language);


        $categories = $this->getCategoriesList($language);

        $blogs = $this->getBlogList($language);

        $onlyOnAfandina = $this->getCars($language,'only_on_afandina','10');

        $specialOffers = $this->getCars($language,'is_flash_sale','10');

        $faqs = $this->getFaqList($language,'show_in_home','10');

        $homeData = $this->getHome($language);

        $contactData = Contact::first();

        $services = $this->getServicesList($language);
        $documents = $this->getDocumentsList($language);
        $locations = $this->getLocationsList($language);
        $response = [
            'header_section'=>[
                'hero_header_title' => $homeData->hero_header_title,
                'hero_header_video_path' => $homeData->hero_header_video_path,
                'social_media_links' => [
                    'facebook' => $contactData->facebook,
                    'twitter' => $contactData->twitter,
                    'instagram' => $contactData->instagram,
                    'snapchat' => $contactData->snapchat,
                ],
                'menu_keys'=>[],
            ],
            'brands_section'=>[
                'brand_title'=>$homeData->brand_title,
                'brands'=>$brands,
            ],
            'categories_section'=>[
                'category_title'=>$homeData->category_title,
                'categories'=>$categories,
            ],
            'only_on_afandina_section'=>[
                'car_only_section_title'=>$homeData->car_only_section_title,
                'car_only_section_paragraph'=>$homeData->car_only_section_paragraph,
                'only_on_afandina'=>$onlyOnAfandina,
            ],
            'special_offers_section'=>[
               'special_offers_title'=>$homeData->special_offers_section_title,
               'special_offers_section_paragraph'=>$homeData->special_offers_section_paragraph,
               'special_offers'=>$specialOffers,
            ],
            'why_choose_us_section'=>[
                'why_choose_us_title'=>$homeData->why_choose_us_section_title,
                'why_choose_us_section_paragraph'=>$homeData->why_choose_us_section_paragraph,
                'services'=> $services,
            ],
//            'blogs_section'=>[
//                'blogs_title'=>$homeData->blogs_title,
//                'blogs'=>$blogs,
//            ],
//            'faqs_section'=>[
//                'faqs_title'=>$homeData->faq_section_title,
//                'faqs_section_paragraph'=>$homeData->faq_section_paragraph,
//                'faqs'=>$faqs,
//            ],
            'location_section'=>[
                'location_title'=>$homeData->where_find_us_section_title,
                'location_paragraph'=>$homeData->where_find_us_section_paragraph,
                'locations'=>$locations,
            ],
            'document_section'=>[
                'document_title'=>$homeData->required_documents_section_title,
                'document_section_paragraph'=>$homeData->required_documents_section_paragraph,
                'documents'=>$documents,
            ],
            'instagram_section'=>[
                'instagram_title'=>"Instagram Videos",
                'instagram_videos'=>[],
            ],
            'footer_section'=>[
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
                    ]
                ]
        ];

        return response()->json([
            'data' => $response,
            'status' =>'success'
        ]);
    }

    public function search(Request $request)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        // Get the search term from the user input
        $searchTerm = $request->input('query');

        // Fetch categories matching the search term
        $categories = Category::join('category_translations', function ($join) use ($language) {
            $join->on('categories.id', '=', 'category_translations.category_id')
                ->where('category_translations.locale', '=', $language);
        })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select('categories.id', 'categories.image_path', 'category_translations.slug', 'category_translations.name')
            ->withCount('cars') // Count the number of cars for each brand
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Fetch brands matching the search term
        $brands = Brand::join('brand_translations', function ($join) use ($language) {
            $join->on('brands.id', '=', 'brand_translations.brand_id')
                ->where('brand_translations.locale', '=', $language);
        })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select('brands.id', 'brands.logo_path', 'brand_translations.slug', 'brand_translations.name')
            ->withCount('cars') // Count the number of cars for each brand
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Fetch cars matching the search term
        $cars = Car::join('car_translations', function ($join) use ($language) {
            $join->on('cars.id', '=', 'car_translations.car_id')
                ->where('car_translations.locale', '=', $language);
        })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select('cars.id', 'cars.default_image_path', 'car_translations.slug', 'car_translations.name')
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Combine results and return as JSON response
        return response()->json([
            "data"=>[
                'categories' => $categories,
                'brands' => $brands,
                'cars' => $cars
            ],
            'status' =>'success'
        ]);
    }




}
