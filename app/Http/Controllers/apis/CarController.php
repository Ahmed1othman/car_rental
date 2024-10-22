<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Car;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{

    use DBTrait;

    public function advancedSearch(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';

        // Start with a base query
        $query = Car::where('is_active',true)->with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }]);

        // Apply filters dynamically based on request query parameters

        // 1. Filter by brand name (translated)
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $key => $value) {
                if ($key == 'brand_id')
                    $query->where('brand_id', $value);
                if ($key == 'category_id')
                    $query->where('category_id', $value);
                if ($key == 'color_id')
                    $query->where('color_id', $value);
                if ($key =='gear_type_id')
                    $query->where('gear_type_id', $value);
                if ($key == 'free_delivery')
                    $query->where('free_delivery', true);

                if ($key=='daily_main_price'){
                    $query->whereBetween($value[0],'daily_main_price', '<=', $value[1])
                    ->orWHereBetween($value[0],'daily_discount_price', '<=', $value[1]);
                }

                if ($key=='weekly_main_price'){
                    $query->whereBetween($value[0],'weekly_main_price', '<=', $value[1])
                        ->orWHereBetween($value[0],'weekly_discount_price', '<=', $value[1]);
                }

                if ($key=='monthly_main_price'){
                    $query->whereBetween($value[0],'monthly_main_price', '<=', $value[1])
                        ->orWHereBetween($value[0],'monthly_discount_price', '<=', $value[1]);
                }

                if ($key == 'door_count')
                    $query->where('door_count', $value);
                if ($key == 'luggage_capacity')
                    $query->where('luggage_capacity', $value);
                if ($key == 'passenger_capacity')
                    $query->where('passenger_capacity', $value);
                if ($key == 'insurance_included')
                    $query->where('insurance_included', true);

                $query->whereHas('translations', function ($q) use ($value,$key) {
                    if ($key=="name")
                        $q->where('name', 'like', '%'. $value. '%');
                });
            }
        }

        // 4. Pagination check
        if ($request->has('paginate') && $request->input('paginate') == 'true') {
            // User wants pagination
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided
            $brands = $query->paginate($perPage);
        } else {
            // No pagination, return all results
            $brands = $query->get();
        }

        // Return the results using a resource
        return BrandResource::collection($brands);
    }


    public function getCarDetails(Request $request,$language){
        // Fetch the main car details along with image details
        $query = DB::table('cars')
            ->select(
                'cars.id',
                'cars.daily_main_price',
                'cars.daily_discount_price',

                'cars.weekly_main_price',
                'cars.weekly_discount_price',

                'cars.monthly_main_price',
                'cars.monthly_discount_price',

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
                'car_translations.name',
                'car_images.file_path',
                'car_images.alt',
                'car_images.type'
            )
            ->leftJoin('car_translations', function ($join) use ($language) {
                $join->on('cars.id', '=', 'car_translations.car_id')
                    ->where('car_translations.locale', '=', $language);
            })
            ->leftJoin('car_images', 'cars.id', '=', 'car_images.car_id')
            ->leftJoin('colors', 'colors.id', '=', 'cars.color_id')
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
            ->groupBy('cars.id','colors.color_code','color_translations.name','category_translations.name','brand_translations.name','cars.default_image_path', 'car_translations.slug', 'car_translations.name', 'car_images.file_path', 'car_images.alt', 'car_images.type');



        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $key => $value) {
                if ($key == 'brand_id')
                    $query->where('brand_id', $value);
                if ($key == 'category_id')
                    $query->where('category_id', $value);
                if ($key == 'color_id')
                    $query->where('color_id', $value);
                if ($key =='gear_type_id')
                    $query->where('gear_type_id', $value);
                if ($key == 'free_delivery')
                    $query->where('free_delivery', true);

                if ($key=='daily_main_price'){
                    $query->whereBetween($value[0],'daily_main_price', '<=', $value[1])
                        ->orWHereBetween($value[0],'daily_discount_price', '<=', $value[1]);
                }

                if ($key=='weekly_main_price'){
                    $query->whereBetween($value[0],'weekly_main_price', '<=', $value[1])
                        ->orWHereBetween($value[0],'weekly_discount_price', '<=', $value[1]);
                }

                if ($key=='monthly_main_price'){
                    $query->whereBetween($value[0],'monthly_main_price', '<=', $value[1])
                        ->orWHereBetween($value[0],'monthly_discount_price', '<=', $value[1]);
                }

                if ($key == 'door_count')
                    $query->where('door_count', $value);
                if ($key == 'luggage_capacity')
                    $query->where('luggage_capacity', $value);
                if ($key == 'passenger_capacity')
                    $query->where('passenger_capacity', $value);
                if ($key == 'insurance_included')
                    $query->where('insurance_included', true);

                $query->whereHas('translations', function ($q) use ($value,$key) {
                    if ($key=="name")
                        $q->where('name', 'like', '%'. $value. '%');
                });
            }
        }



        if ($request->has('paginate') && $request->input('paginate') == 'true') {
            // User wants pagination
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided
            $cars = $query->paginate($perPage);
        }
        else
            $cars = $query->get();
        // Now process the results to group images by car
        $carsGrouped = [];

        foreach ($cars as $car) {
            if (!isset($carsGrouped[$car->id])) {
                // Initialize car entry with details and an empty images array
                $carsGrouped[$car->id] = [
                    'id' => $car->id,
                    'default_image_path' => $car->default_image_path,
                    'slug' => $car->slug,
                    'name' => $car->name,
                    'daily_main_price'=> $car->daily_main_price,
                    'daily_discount_price' => $car->daily_discount_price,
                    'weekly_main_price' => $car->weekly_main_price,
                    'weekly_discount_price' => $car->weekly_discount_price,
                    'monthly_main_price' => $car->monthly_main_price,
                    'monthly_discount_price' => $car->monthly_discount_price,
                    'door_count' => $car->door_count,
                    'luggage_capacity' => $car->luggage_capacity,
                    'passenger_capacity' => $car->passenger_capacity,
                    'insurance_included' => $car->insurance_included,
                    'free_delivery' => $car->free_delivery,
                    'is_featured' => $car->is_featured,
                    'is_flash_sale' => $car->is_flash_sale,
                    'status' => $car->status,
                    'color_name' => $car->color_name,
                    'color_code' => $car->color_code,
                    'brand_name' => $car->brand_name,
                    'category_name' => $car->category_name,
                    'gear_type_id' => $car->gear_type_id,
                    'images' => []

                ];
            }

            // Add image to the car's images array (only if it exists)
            if ($car->file_path) {
                $carsGrouped[$car->id]['images'][] = [
                    'file_path' => $car->file_path,
                    'alt' => $car->alt,
                    'type' => $car->type
                ];
            }
        }

        // If you're paginating, convert the results back into a Laravel paginator
        if ($request->has('paginate') && $request->input('paginate') == 'true') {
            $paginatedResult = $cars->setCollection(collect(array_values($carsGrouped)));
            return $paginatedResult;
        }

        // Return the grouped result as a collection
        return collect(array_values($carsGrouped));
    }


}
