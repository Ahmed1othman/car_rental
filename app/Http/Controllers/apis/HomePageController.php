<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\HomeResource;
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
        $homeData = $this->getHome($language);
        $per_page = $homeData->per_page ?? 10; // Get per_page from database, fallback to 10
        
        $brands = $this->getBrandsList($language);
        $onlyOnAfandina = $this->getCars($language,'only_on_afandina', null, true, $per_page);
        $specialOffers = $this->getCars($language,'is_flash_sale', null, true, $per_page);
        $advertisements = $this->getAdvertisements($language);
        $contactData = Contact::first();
        $services = $this->getServicesList($language);
        $documents = $this->getDocumentsList($language);
        $locations = $this->getLocationsList($language);
        $shortVideos = Short_video::get();

        $data = [
            'homeData' => $homeData,
            'contactData' => $contactData,
            'onlyOnAfandina' => $onlyOnAfandina,
            'advertisements' => $advertisements,
            'specialOffers' => $specialOffers,
            'services' => $services,
            'locations' => $locations,
            'documents' => $documents,
            'shortVideos' => $shortVideos,
        ];

        return new HomeResource($data);
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
            ->select('cars.id', 'cars.default_image_path', 'cars.slug', 'car_translations.name')
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
