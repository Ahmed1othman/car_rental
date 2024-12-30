<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\SeoResource;
use App\Http\Resources\ShortVideoResource;
use App\Models\About;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Home;
use App\Models\HomeTranslation;
use App\Models\Service;
use App\Models\Short_video;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $brands = $this->getBrandsList($language);


//        $categories = $this->getCategoriesList($language);

//        $blogs = $this->getBlogList($language);

        $onlyOnAfandina = $this->getCars($language,'only_on_afandina',10);

        $specialOffers = $this->getCars($language,'is_flash_sale',10);

//        $faqs = $this->getFaqList($language,'show_in_home','10');

        $homeData = $this->getHome($language);

        $advertisements = $this->getAdvertisements($language);

        $contactData = Contact::first();

        $services = $this->getServicesList($language);
        $documents = $this->getDocumentsList($language);
        $locations = $this->getLocationsList($language);
        $shortVideos = Short_video::get();

        $response = [
            'header_section'=>[
                'hero_header_title' => $homeData->translations->first()->hero_header_title,
                'hero_header_video_path' => $homeData->hero_header_video_path,
                'hero_header_image_path' => $homeData->hero_header_image_path,
                'hero_media_type' => $homeData->hero_type,
                'social_media_links' => [
                    'facebook' => $contactData->facebook,
                    'twitter' => $contactData->twitter,
                    'instagram' => $contactData->instagram,
                    'snapchat' => $contactData->snapchat,
                ],
                'menu_keys'=>[],
            ],
            'only_on_afandina_section'=>[
                'car_only_section_title'=>$homeData->translations->first()->car_only_section_title,
                'car_only_section_paragraph'=>$homeData->translations->first()->car_only_section_paragraph,
                'only_on_afandina'=>$onlyOnAfandina,
            ],

            'advertisements'=>[
                'advertisements'=>AdvertisementResource::collection($advertisements),
            ],
            'special_offers_section'=>[
               'special_offers_title'=>$homeData->translations->first()->special_offers_section_title,
               'special_offers_section_paragraph'=>$homeData->translations->first()->special_offers_section_paragraph,
               'special_offers'=>$specialOffers,
            ],
            'why_choose_us_section'=>[
                'why_choose_us_title'=>$homeData->translations->first()->why_choose_us_section_title,
                'why_choose_us_section_paragraph'=>$homeData->translations->first()->why_choose_us_section_paragraph,
                'services'=> $services,
            ],

            'where_find_us'=>[
                'where_find_us_section_title'=>$homeData->translations->first()->where_find_us_section_title,
                'where_find_us_section_paragraph'=>$homeData->translations->first()->where_find_us_section_paragraph,
                'locations'=> $locations,
            ],
            'document_section'=>[
                'document_title'=>$homeData->translations->first()->required_documents_section_title,
                'document_section_paragraph'=>$homeData->translations->first()->required_documents_section_paragraph,
                'documents'=>$documents,
            ],
            'short_videos_section'=>[
                'short_videos_title'=>"Instagram Videos",
                'short_videos'=> ShortVideoResource::collection($shortVideos),
            ],
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
            ->select('categories.id', 'categories.image_path', 'categories.slug', 'category_translations.name')
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
            ->select('brands.id', 'brands.logo_path', 'brands.slug', 'brand_translations.name')
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

    public function SEO(){
        $home = Home::first();
        $aboutAs = About::first();
        return [
            'home'=>new SeoResource($home),
            'about_us'=>new SeoResource($aboutAs),
        ];
    }

}
