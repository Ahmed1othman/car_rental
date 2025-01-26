<?php

namespace App\Traits;

use App\Models\About;
use App\Models\Advertisement;
use App\Models\Contact;
use App\Models\Currency;
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
    public function getCurrenciesList()
    {
        return Currency::where('is_active', true)->get();
    }
    public function getBrandsList($language)
    {
        return DB::table('brands')
            ->select('brands.id', 'brands.logo_path', 'brands.slug', 'brand_translations.name', DB::raw('COUNT(cars.id) as cars_count'))
            ->leftJoin('brand_translations', function ($join) use ($language) {
                $join->on('brands.id', '=', 'brand_translations.brand_id')
                    ->where('brand_translations.locale', '=', $language);
            })
            ->leftJoin('cars', 'brands.id', '=', 'cars.brand_id') // Assuming there is a cars table with a brand_id
            ->where('brands.is_active', true)
            ->groupBy('brands.id', 'brands.slug', 'brand_translations.name', 'brands.logo_path')
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
            ->select('categories.id', 'categories.image_path', 'categories.slug', 'category_translations.name', DB::raw('COUNT(cars.id) as cars_count'))
            ->leftJoin('category_translations', function ($join) use ($language) {
                $join->on('categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', '=', $language);
            })
            ->leftJoin('cars', 'categories.id', '=', 'cars.category_id') // Assuming there is a cars table with a category_id
            ->where('categories.is_active', true)
            ->groupBy('categories.id', 'categories.slug', 'category_translations.name', 'categories.image_path')
            ->get();
    }

    public function getBlogList($language, $limit = 10,$conditions = [])
    {
        return DB::table('blogs')
        ->select(
            'blogs.id',
            'blogs.created_at',
            'blogs.image_path',
            'blogs.slug',
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
            'blogs.slug',
            'blog_translations.title',
            'blog_translations.content'
        )
        ->get();
    }

    public function getServicesList($language)
    {
        return DB::table('services')
            ->select('services.id', 'services.slug', 'service_translations.name', 'service_translations.description')
            ->leftJoin('service_translations', function ($join) use ($language) {
                $join->on('services.id', '=', 'service_translations.service_id')
                    ->where('service_translations.locale', '=', $language);
            })
            ->where('services.is_active', true)
            ->where('services.show_in_home', true)
            ->groupBy('services.id', 'services.slug', 'service_translations.description', 'service_translations.name')
            ->limit(4)
            ->get();
    }
    public function getDocumentsList($language)
    {
        return DB::table('documents')
            ->select('documents.id','documents.for', 'documents.slug', 'document_translations.content')
            ->leftJoin('document_translations', function ($join) use ($language) {
                $join->on('documents.id', '=', 'document_translations.document_id')
                    ->where('document_translations.locale', '=', $language);
            })
            ->where('documents.is_active', true)
            ->groupBy('documents.id','documents.for', 'documents.slug', 'document_translations.content')
            ->get();
    }

    public function getLocationsList($language)
    {
        return DB::table('locations')
            ->select('locations.id', 'locations.slug', 'location_translations.name')
            ->leftJoin('location_translations', function ($join) use ($language) {
                $join->on('locations.id', '=', 'location_translations.location_id')
                    ->where('location_translations.locale', '=', $language);
            })
            ->where('locations.is_active', true)
            ->groupBy('locations.id', 'locations.slug', 'location_translations.name')
            ->get();
    }



    public function getCars($language, $condition, $limit = null, $paginate = null, $per_page = 10)
    {
        $currentCurrency = $this->getCurrency($language);
        $currency = \App\Models\Currency::find(app('currency_id'));
        $currencyLanguage = $currency->translations->where('locale', $language)->first();

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
                'cars.slug',
                'car_translations.name'
            )
            ->leftJoin('car_translations', function ($join) use ($language,$currency,$currencyLanguage) {
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
            ->when($condition === 'only_on_afandina', function ($query) {
                return $query->where('cars.only_on_afandina', true);
            })
            ->when($condition === 'is_flash_sale', function ($query) {
                return $query->where('cars.is_flash_sale', true);
            });

        // Fetch all images separately
        $carImages = DB::table('car_images')
            ->whereIn('car_id', $cars->pluck('id'))
            ->get()
            ->groupBy('car_id');

        // Attach images to each car
        $cars->transform(function ($car) use ($carImages, $currentCurrency,$currencyLanguage,$currency) {
            $defaultImage = collect([
                [
                    'file_path' => $car->default_image_path,
                    'alt' => 'Default Image',  // Customize if needed
                    'type' => 'image',         // Default type
                ]
            ]);

            $car->images = $defaultImage->concat(
                $carImages->get($car->id, collect())->map(function ($image) {
                    return [
                        'file_path' => $image->file_path,
                        'alt' => $image->alt,
                        'type' => $image->type
                    ];
                })
            );
            // Convert prices based on currency
            $car->daily_main_price = ceil($car->daily_main_price * $currentCurrency->exchange_rate);
            $car->daily_discount_price = ceil($car->daily_discount_price * $currentCurrency->exchange_rate);
            $car->weekly_main_price = ceil($car->weekly_main_price * $currentCurrency->exchange_rate);
            $car->weekly_discount_price = ceil($car->weekly_discount_price * $currentCurrency->exchange_rate);
            $car->monthly_main_price = ceil($car->monthly_main_price * $currentCurrency->exchange_rate);
            $car->monthly_discount_price = ceil($car->monthly_discount_price * $currentCurrency->exchange_rate);

            $car->no_deposit = 1;
            $car->discount_rate = ceil(($car->daily_main_price - $car->daily_discount_price) * 100 / $car->daily_main_price);

            $car->currency=[
                'name' => $currencyLanguage->name,
                'code' => $currency->code,
                'symbol' => $currency->symbol,
            ];
            return $car;
        });

        // Apply pagination if requested
        if ($paginate) {
            return $cars->paginate($per_page);
        }

        // Apply limit if specified
        if ($limit) {
            $cars->limit($limit);
        }

        return $cars->get();
    }


    public function getFaqList($language, $condition = null, $limit = null, $paginate = null)
    {
        $query = DB::table('faqs')
            ->select(
                'faqs.id',
                'faqs.slug',
                'faq_translations.question',
                'faq_translations.answer'
            )
            ->leftJoin('faq_translations', function ($join) use ($language) {
                $join->on('faqs.id', '=', 'faq_translations.faq_id')
                    ->where('faq_translations.locale', '=', $language);
            })
            ->where('faqs.is_active', true);

        // Apply additional conditions if provided
        if ($condition) {
            foreach ($condition as $key => $value) {
                $query->where($key, $value);
            }
        }

        // Apply limit if provided
        if ($limit) {
            $query->limit($limit);
        }

        // Apply pagination if requested
        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }
}
