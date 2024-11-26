<?php

namespace App\Traits;

use App\Models\About;
use App\Models\Advertisement;
use App\Models\Contact;
use App\Models\Home;
use Illuminate\Support\Facades\DB;

trait DBTrait
{
    public function getCurrency(){
        if (app('currency_id'))
            return DB::table('currencies')->where('id', app('currency_id'))->first();
        else
            return DB::table('currencies')->where('is_default',true)->first();
    }
    public function getLanguagesList($language)
    {
        return DB::table('languages')
            ->select(
                'languages.id',
                'languages.code',
                'languages.flag',
                'languages.name',
            )
            ->where('languages.is_active', true)
            ->get();
    }
    public function getCurrenciesList($language)
    {
        return DB::table('currencies')
            ->select(
                'currencies.id',
                'currencies.code',
                'currencies.name',
                'currencies.symbol',
                'currencies.exchange_rate',
                'currencies.is_default',
            )
            ->where('currencies.is_active', true)
            ->get();
    }
    public function getBrandsList($language)
    {
        return DB::table('brands')
            ->select('brands.id', 'brands.logo_path', 'brand_translations.slug', 'brand_translations.name', DB::raw('COUNT(cars.id) as cars_count'))
            ->leftJoin('brand_translations', function ($join) use ($language) {
                $join->on('brands.id', '=', 'brand_translations.brand_id')
                    ->where('brand_translations.locale', '=', $language);
            })
            ->leftJoin('cars', 'brands.id', '=', 'cars.brand_id') // Assuming there is a cars table with a brand_id
            ->where('brands.is_active', true)
            ->groupBy('brands.id', 'brand_translations.slug', 'brand_translations.name', 'brands.logo_path')
            ->get();
    }

    public function getHome($language)
    {
        return Home::with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }])->first();
    }


    public function getAbout($language)
    {
        return About::with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }])->first();
    }
    public function getContact(){
        return Contact::first();
    }

    public function getAdvertisements($language)
    {
        return Advertisement::with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }],'advertisement_position')->get();
    }

    public function getCategoriesList($language)
    {
        return DB::table('categories')
            ->select('categories.id', 'categories.image_path', 'category_translations.slug', 'category_translations.name', DB::raw('COUNT(cars.id) as cars_count'))
            ->leftJoin('category_translations', function ($join) use ($language) {
                $join->on('categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', '=', $language);
            })
            ->leftJoin('cars', 'categories.id', '=', 'cars.category_id') // Assuming there is a cars table with a category_id
            ->where('categories.is_active', true)
            ->groupBy('categories.id', 'category_translations.slug', 'category_translations.name', 'categories.image_path')
            ->get();
    }

    public function getBlogList($language, $limit = 10,$conditions = [])
    {
        return DB::table('blogs')
        ->select(
            'blogs.id',
            'blogs.created_at',
            'blogs.image_path',
            'blog_translations.slug',
            'blog_translations.title',
            'blog_translations.content'
        )
        ->leftJoin('blog_translations', function ($join) use ($language) {
            $join->on('blogs.id', '=', 'blog_translations.blog_id')
                ->where('blog_translations.locale', '=', $language);
        })
        ->where('blogs.is_active', 1)
        ->groupBy(
            'blogs.id',
            'blogs.created_at',
            'blogs.image_path',
            'blog_translations.slug',
            'blog_translations.title',
            'blog_translations.content'
        )
        ->get();
    }

    public function getServicesList($language)
    {
        return DB::table('services')
            ->select('services.id', 'service_translations.slug', 'service_translations.name','service_translations.description')
            ->leftJoin('service_translations', function ($join) use ($language) {
                $join->on('services.id', '=', 'service_translations.service_id')
                    ->where('service_translations.locale', '=', $language);
            })
            ->where('services.is_active', true)
            ->where('services.show_in_home', true)
            ->groupBy('services.id', 'service_translations.slug', 'service_translations.description','service_translations.name')
            ->limit(4)
            ->get();
    }
    public function getDocumentsList($language)
    {
        return DB::table('documents')
            ->select('documents.id','documents.for', 'document_translations.slug', 'document_translations.content')
            ->leftJoin('document_translations', function ($join) use ($language) {
                $join->on('documents.id', '=', 'document_translations.document_id')
                    ->where('document_translations.locale', '=', $language);
            })
            ->where('documents.is_active', true)
            ->groupBy('documents.id','documents.for', 'document_translations.slug', 'document_translations.content')
            ->get();
    }

    public function getLocationsList($language)
    {
        return DB::table('locations')
            ->select('locations.id', 'location_translations.slug', 'location_translations.name')
            ->leftJoin('location_translations', function ($join) use ($language) {
                $join->on('locations.id', '=', 'location_translations.location_id')
                    ->where('location_translations.locale', '=', $language);
            })
            ->where('locations.is_active', true)
            ->groupBy('locations.id', 'location_translations.slug', 'location_translations.name')
            ->get();
    }

//    public function getCars($language, $condition, $limit = null, $paginate = null)
//    {
//        $currentCurrency = $this->getCurrency($language);
//        // Fetch the main car details along with image details
//        $query = DB::table('cars')
//            ->select(
//                'cars.id',
//                'cars.daily_main_price',
//                'cars.daily_discount_price',
//
//                'cars.weekly_main_price',
//                'cars.weekly_discount_price',
//
//                'cars.monthly_main_price',
//                'cars.monthly_discount_price',
//
//                'cars.door_count',
//                'cars.luggage_capacity',
//                'cars.passenger_capacity',
//                'cars.insurance_included',
//                'cars.free_delivery',
//                'cars.is_featured',
//                'cars.is_flash_sale',
//                'cars.status',
//                'cars.gear_type_id',
//
//                'color_translations.name as color_name',
//                'colors.color_code',
//                'brand_translations.name as brand_name',
//                'category_translations.name as category_name',
//
//                'cars.default_image_path',
//                'car_translations.slug',
//                'car_translations.name',
//                'car_images.file_path',
//                'car_images.alt',
//                'car_images.type'
//            )
//            ->distinct()
//            ->leftJoin('car_translations', function ($join) use ($language) {
//                $join->on('cars.id', '=', 'car_translations.car_id')
//                    ->where('car_translations.locale', '=', $language);
//            })
//            ->leftJoin('car_images', 'cars.id', '=', 'car_images.car_id')
//            ->leftJoin('colors', 'colors.id', '=', 'cars.color_id')
//            ->leftJoin('brands', 'brands.id', '=', 'cars.brand_id')
//            ->leftJoin('categories', 'categories.id', '=', 'cars.category_id')
//
//            ->leftJoin('color_translations', function ($join) use ($language) {
//                $join->on('colors.id', '=', 'color_translations.color_id')
//                    ->where('color_translations.locale', '=', $language);
//            })
//            ->leftJoin('brand_translations', function ($join) use ($language) {
//                $join->on('brands.id', '=', 'brand_translations.brand_id')
//                    ->where('brand_translations.locale', '=', $language);
//            })
//            ->leftJoin('category_translations', function ($join) use ($language) {
//                $join->on('categories.id', '=', 'category_translations.category_id')
//                    ->where('category_translations.locale', '=', $language);
//            })
//            ->where('cars.is_active', true)
//            ->where('cars.'.$condition, true);
////            ->groupBy('cars.id','colors.color_code','color_translations.name','category_translations.name','brand_translations.name','cars.default_image_path', 'car_translations.slug', 'car_translations.name', 'car_images.file_path', 'car_images.alt', 'car_images.type');
//
//        // Apply pagination or limit if provided
//        if ($paginate) {
//            $cars = $query->paginate($paginate);
//        } elseif ($limit) {
//            $cars = $query->limit(10)->get();
//        } else {
//            $cars = $query->get();
//        }
//
//        // Now process the results to group images by car
//        $carsGrouped = [];
//        foreach ($cars as $car) {
//            if (!isset($carsGrouped[$car->id])) {
//                // Initialize car entry with details and an empty images array
//                $carsGrouped[$car->id] = [
//                    'id' => $car->id,
//                    'default_image_path' => $car->default_image_path,
//                    'slug' => $car->slug,
//                    'name' => $car->name,
//                    'daily_main_price'=> ceil($car->daily_main_price * $currentCurrency->exchange_rate),
//                    'daily_discount_price' => ceil($car->daily_discount_price* $currentCurrency->exchange_rate),
//                    'weekly_main_price' => ceil($car->weekly_main_price* $currentCurrency->exchange_rate),
//                    'weekly_discount_price' => ceil($car->weekly_discount_price* $currentCurrency->exchange_rate),
//                    'monthly_main_price' => ceil($car->monthly_main_price* $currentCurrency->exchange_rate),
//                    'monthly_discount_price' => ceil($car->monthly_discount_price* $currentCurrency->exchange_rate),
//                    'door_count' => $car->door_count,
//                    'luggage_capacity' => $car->luggage_capacity,
//                    'passenger_capacity' => $car->passenger_capacity,
//                    'insurance_included' => $car->insurance_included,
//                    'free_delivery' => $car->free_delivery,
//                    'is_featured' => $car->is_featured,
//                    'is_flash_sale' => $car->is_flash_sale,
//                    'status' => $car->status,
//                    'color_name' => $car->color_name,
//                    'color_code' => $car->color_code,
//                    'brand_name' => $car->brand_name,
//                    'category_name' => $car->category_name,
//                    'gear_type_id' => $car->gear_type_id, 'images' => []
//                ];
//            }
//
//            // Add image to the car's images array (only if it exists)
//            if ($car->file_path) {
//                $carsGrouped[$car->id]['images'][] = [
//                    'file_path' => $car->file_path,
//                    'alt' => $car->alt,
//                    'type' => $car->type
//                ];
//            }
//        }
//
//        // If you're paginating, convert the results back into a Laravel paginator
//        if ($paginate) {
//            $paginatedResult = $cars->setCollection(collect(array_values($carsGrouped)));
//            return $paginatedResult;
//        }
//
//        // Return the grouped result as a collection
//        return collect(array_values($carsGrouped));
//    }


    public function getCars($language, $condition, $limit = null, $paginate = null)
    {
        $currentCurrency = $this->getCurrency($language);

        // Fetch car details without images
        $cars = DB::table('cars')
            ->select(
                'cars.id',
                'cars.daily_main_price',
                'cars.daily_discount_price',
                'cars.weekly_main_price',
                'cars.weekly_discount_price',
                'cars.monthly_main_price',
                'cars.monthly_discount_price',
                'cars.daily_mileage_included',
                'cars.weekly_mileage_included',
                'cars.monthly_mileage_included',
                'cars.crypto_payment_accepted',
                'years.year',
                'cars.door_count',
                'cars.luggage_capacity',
                'cars.passenger_capacity',
                'cars.insurance_included',
                'cars.free_delivery',
                'cars.is_featured',
                'cars.is_flash_sale',
                'cars.status',
                'cars.gear_type_id',
                'color_translations.name as color_name',
                'colors.color_code',
                'brand_translations.name as brand_name',
                'category_translations.name as category_name',
                'cars.default_image_path',
                'car_translations.slug',
                'car_translations.name'
            )
            ->leftJoin('car_translations', function ($join) use ($language) {
                $join->on('cars.id', '=', 'car_translations.car_id')
                    ->where('car_translations.locale', '=', $language);
            })
            ->leftJoin('colors', 'colors.id', '=', 'cars.color_id')
            ->leftJoin('years', 'years.id', '=', 'cars.year_id')
            ->leftJoin('brands', 'brands.id', '=', 'cars.brand_id')
            ->leftJoin('categories', 'categories.id', '=', 'cars.category_id')
            ->leftJoin('color_translations', function ($join) use ($language) {
                $join->on('colors.id', '=', 'color_translations.color_id')
                    ->where('color_translations.locale', '=', $language);
            })
            ->leftJoin('brand_translations', function ($join) use ($language) {
                $join->on('brands.id', '=', 'brand_translations.brand_id')
                    ->where('brand_translations.locale', '=', $language);
            })
            ->leftJoin('category_translations', function ($join) use ($language) {
                $join->on('categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', '=', $language);
            })
            ->where('cars.is_active', true)
            ->where('cars.' . $condition, true)
            ->limit($limit)
            ->get();

        // Fetch all images separately
        $carImages = DB::table('car_images')
            ->whereIn('car_id', $cars->pluck('id'))
            ->get()
            ->groupBy('car_id');

        // Attach images to each car
        $cars->transform(function ($car) use ($carImages, $currentCurrency) {
            $car->images = $carImages->get($car->id, collect())->map(function ($image) {
                return [
                    'file_path' => $image->file_path,
                    'alt' => $image->alt,
                    'type' => $image->type
                ];
            });

            // Convert prices based on currency
            $car->daily_main_price = ceil($car->daily_main_price * $currentCurrency->exchange_rate);
            $car->daily_discount_price = ceil($car->daily_discount_price * $currentCurrency->exchange_rate);
            $car->weekly_main_price = ceil($car->weekly_main_price * $currentCurrency->exchange_rate);
            $car->weekly_discount_price = ceil($car->weekly_discount_price * $currentCurrency->exchange_rate);
            $car->monthly_main_price = ceil($car->monthly_main_price * $currentCurrency->exchange_rate);
            $car->monthly_discount_price = ceil($car->monthly_discount_price * $currentCurrency->exchange_rate);

            $car->no_debosit = 1;
            $car->discount_rate = (($car->daily_main_price - $car->daily_discount_price) * 100 / $car->daily_main_price);
            return $car;
        });

        return $cars;
    }


    public function getFaqList($language,$condition=null, $limit = null,$paginate = null)
    {
        $query =  DB::table('faqs')
            ->select(
                'faqs.id',
                'faqs.created_at',
                'faq_translations.question',
                'faq_translations.answer',
                'faq_translations.slug',
            )
            ->leftJoin('faq_translations', function ($join) use ($language) {
                $join->on('faqs.id', '=', 'faq_translations.faq_id')
                    ->where('faq_translations.locale', '=', $language);
            })
            ->where('faqs.is_active', 1)
            ->where('faqs.'.$condition, 1)
            ->groupBy(
                'faqs.id',
                'faqs.created_at',
                'faq_translations.slug',
                'faq_translations.question',
                'faq_translations.answer'
            );



            // Apply pagination or limit if provided
        if ($paginate) {
            $data = $query->paginate($paginate);
        } elseif ($limit) {
            $data = $query->limit($limit)->get();
        } else {
            $data = $query->get();
        }

        return $data;
    }

}
