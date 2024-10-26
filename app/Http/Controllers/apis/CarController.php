<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CarResource;
use App\Models\Brand;
use App\Models\Car;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{

    use DBTrait;

//    public function advancedSearch(Request $request){
//        $language = $request->header('Accept-Language') ?? 'en';
//
//        // Start with a base query
//        $query = Car::select(
//            'cars.id',
//            'cars.daily_main_price',
//            'cars.daily_discount_price',
//
//            'cars.weekly_main_price',
//            'cars.weekly_discount_price',
//
//            'cars.monthly_main_price',
//            'cars.monthly_discount_price',
//
//            'cars.door_count',
//            'cars.luggage_capacity',
//            'cars.passenger_capacity',
//            'cars.insurance_included',
//            'cars.free_delivery',
//            'cars.is_featured',
//            'cars.is_flash_sale',
//            'cars.status',
//            'cars.gear_type_id',
//
//            'color_translations.name as color_name',
//            'colors.color_code',
//            'brand_translations.name as brand_name',
//            'category_translations.name as category_name',
//
//
//            'cars.default_image_path',
//            'car_translations.slug',
//            'car_translations.name',
//            'car_images.file_path',
//            'car_images.alt',
//            'car_images.type'
//        )
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
//            ->where('cars.is_active', true);
//
//
//        // 1. Filter by brand name (translated)
//        if ($request->has('filters')) {
//            $filters = $request->input('filters');
//            foreach ($filters as $key => $value) {
//                if ($key == 'brand_id')
//                    $query->where('brand_id', $value);
//                if ($key == 'category_id')
//                    $query->where('category_id', $value);
//                if ($key == 'color_id')
//                    $query->where('color_id', $value);
//                if ($key =='gear_type_id')
//                    $query->where('gear_type_id', $value);
//                if ($key == 'free_delivery')
//                    $query->where('free_delivery', true);
//
//                if ($key=='daily_main_price'){
//                    $query->whereBetween($value[0],'daily_main_price', '<=', $value[1])
//                    ->orWHereBetween($value[0],'daily_discount_price', '<=', $value[1]);
//                }
//
//                if ($key=='weekly_main_price'){
//                    $query->whereBetween($value[0],'weekly_main_price', '<=', $value[1])
//                        ->orWHereBetween($value[0],'weekly_discount_price', '<=', $value[1]);
//                }
//
//                if ($key=='monthly_main_price'){
//                    $query->whereBetween($value[0],'monthly_main_price', '<=', $value[1])
//                        ->orWHereBetween($value[0],'monthly_discount_price', '<=', $value[1]);
//                }
//
//                if ($key == 'door_count')
//                    $query->where('door_count', $value);
//                if ($key == 'luggage_capacity')
//                    $query->where('luggage_capacity', $value);
//                if ($key == 'passenger_capacity')
//                    $query->where('passenger_capacity', $value);
//                if ($key == 'insurance_included')
//                    $query->where('insurance_included', true);
//
//                $query->whereHas('translations', function ($q) use ($value,$key) {
//                    if ($key=="word")
//                        $q->where('name', 'like', '%'. $value. '%');
//                });
//
//
//
//            }
//        }
//
//        // 4. Pagination check
//        if ($request->has('paginate') && $request->input('paginate') == 'true') {
//            // User wants pagination
//            $perPage = $request->input('per_page', 10); // Default to 10 if not provided
//            $cars = $query->paginate($perPage);
//        } else {
//            // No pagination, return all results
//            $cars = $query->get();
//        }
//
//
//        return $cars;
//        // Return the results using a resource
//        return CarResource::collection($cars);
//    }

    public function advancedSearch(Request $request)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        app()->setLocale($language);

        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);

        if ($request->has('filters')) {
            $filters = $request->input('filters');

            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'brand_id':
                    case 'category_id':
                    case 'color_id':
                    case 'gear_type_id':
                    case 'door_count':
                    case 'luggage_capacity':
                    case 'passenger_capacity':
                        $query->where($key, $value);
                        break;

                    case 'free_delivery':
                    case 'insurance_included':
                        $query->where($key, true);
                        break;

                    case 'daily_main_price':
                        $query->whereBetween('daily_main_price', $value)
                            ->orWhereBetween('daily_discount_price', $value);
                        break;

                    case 'weekly_main_price':
                        $query->whereBetween('weekly_main_price', $value)
                            ->orWhereBetween('weekly_discount_price', $value);
                        break;

                    case 'monthly_main_price':
                        $query->whereBetween('monthly_main_price', $value)
                            ->orWhereBetween('monthly_discount_price', $value);
                        break;

                    case 'word':
                        $query->whereHas('translations', function ($q) use ($value) {
                            $q->where('name', 'like', '%' . $value . '%');
                        });
                        break;
                }
            }
        }

        $cars = $request->input('paginate') === 'true'
            ? $query->paginate($request->input('per_page', 10))
            : $query->get();

        return CarResource::collection($cars);
    }




}
