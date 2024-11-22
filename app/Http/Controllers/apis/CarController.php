<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\DetailedCarResource;
use App\Models\Brand;
use App\Models\Car;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{

    use DBTrait;

    public function show(Request $request ,$slug){

        $language = $request->header('Accept-Language') ?? 'en';
        $car = Car::whereHas('translations', function ($q) use ($slug) {
            $q->where('slug',$slug);
        })->firstOrFail();
        return new DetailedCarResource($car);
    }
    public function advancedSearch(Request $request)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        app()->setLocale($language);

        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);

        if ($request->has('filters')) {
            $filters = $request->input('filters');

            // Array-based ID filters
            $idFilters = ['brand_id', 'category_id', 'color_id', 'gear_type_id'];
            foreach ($idFilters as $filter) {
                if (isset($filters[$filter]) && !empty($filters[$filter])) {
                    $query->whereIn($filter, (array)$filters[$filter]);
                }
            }

            // Numeric value filters
            $numericFilters = ['door_count', 'luggage_capacity', 'passenger_capacity'];
            foreach ($numericFilters as $filter) {
                if (isset($filters[$filter]) && $filters[$filter] !== '') {
                    $query->where($filter, $filters[$filter]);
                }
            }

            // Boolean filters
            $booleanFilters = ['free_delivery', 'insurance_included', 'only_on_afandina', 'is_flash_sale'];
            foreach ($booleanFilters as $filter) {
                if (isset($filters[$filter]) && $filters[$filter] !== '') {
                    $query->where($filter, (bool)$filters[$filter]);
                }
            }

            // Price range filters
            $priceFilters = [
                'daily_main_price' => ['daily_main_price', 'daily_discount_price'],
                'weekly_main_price' => ['weekly_main_price', 'weekly_discount_price'],
                'monthly_main_price' => ['monthly_main_price', 'monthly_discount_price']
            ];

            foreach ($priceFilters as $filterKey => $priceColumns) {
                if (isset($filters[$filterKey]) && is_array($filters[$filterKey]) && count($filters[$filterKey]) == 2) {
                    $query->where(function($q) use ($filters, $filterKey, $priceColumns) {
                        $q->whereBetween($priceColumns[0], $filters[$filterKey])
                            ->orWhereBetween($priceColumns[1], $filters[$filterKey]);
                    });
                }
            }

            // Word search
            if (isset($filters['word']) && !empty($filters['word'])) {
                $query->whereHas('translations', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['word'] . '%');
                });
            }
        }

        $cars = $request->input('paginate') === 'true'
            ? $query->paginate($request->input('per_page', 10))
            : $query->get();

        return CarResource::collection($cars);
    }


}
