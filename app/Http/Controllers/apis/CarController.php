<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DetailedBrandResource;
use App\Http\Resources\DetailedCarResource;
use App\Http\Resources\DetailedCategoryResource;
use App\Http\Resources\DetailedLocationResource;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Location;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class CarController extends Controller
{

    use DBTrait;

    public function show(Request $request, $slug)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        $car = Car::where('slug', $slug)->firstOrFail();
        return new DetailedCarResource($car);
    }

    public function advancedSearch(Request $request)
    {
        $language = $request->header('Accept-Language', 'en');
        app()->setLocale($language);

        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);

        // Apply filters if they exist
        if ($request->has('filters')) {
            $filters = $request->input('filters');

            // Array-based ID filters
            collect(['brand_id', 'category_id', 'color_id', 'gear_type_id'])
                ->each(function ($filter) use ($query, $filters) {
                    if (!empty($filters[$filter])) {
                        $query->whereIn($filter, (array)$filters[$filter]);
                    }
                });

            // Numeric value filters
            collect(['door_count', 'luggage_capacity', 'passenger_capacity'])
                ->each(function ($filter) use ($query, $filters) {
                    if (isset($filters[$filter]) && $filters[$filter] !== '') {
                        $query->where($filter, $filters[$filter]);
                    }
                });

            // Boolean filters
            collect(['free_delivery', 'insurance_included', 'only_on_afandina', 'is_flash_sale'])
                ->each(function ($filter) use ($query, $filters) {
                    if (isset($filters[$filter]) && $filters[$filter] !== '') {
                        $query->where($filter, (bool)$filters[$filter]);
                    }
                });

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

            // Word search in translations
            if (!empty($filters['word'])) {
                $query->whereHas('translations', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['word'] . '%');
                });
            }
        }

        // Handle sorting with validation
        $allowedSortFields = [
            'created_at',
            'daily_main_price',
            'weekly_main_price',
            'monthly_main_price',
            'passenger_capacity',
            'door_count'
        ];

        $sortField = in_array($request->input('sort_by'), $allowedSortFields)
            ? $request->input('sort_by')
            : 'created_at';

        $sortDirection = $request->input('sort_direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Handle pagination with validation
        $perPage = min(max($request->input('per_page', 100), 1), 100);

        // Return paginated resource collection
        return CarResource::collection(
            $query->paginate($perPage)->withQueryString()
        );
    }


    public function getBrandCars(Request $request,$slug){
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);
        $query->whereHas('brand', function ($q) use ($brand) {
            $q->where('id', $brand->id);
        });

        // Handle sorting with validation
        $allowedSortFields = [
            'created_at',
            'daily_main_price',
            'weekly_main_price',
            'monthly_main_price',
            'passenger_capacity',
            'door_count'
        ];

        $sortField = in_array($request->input('sort_by'), $allowedSortFields)
            ? $request->input('sort_by')
            : 'created_at';

        $sortDirection = $request->input('sort_direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Handle pagination with validation
        $perPage = min(max($request->input('per_page', 100), 1), 100);


        return [
            'cars' =>  CarResource::collection($query->paginate($perPage)->withQueryString()),
            'brands' => new DetailedBrandResource($brand)
        ];

    }

    public function getCategoryCars(Request $request,$slug){
        $language = $request->header('Accept-Language', 'en');
        app()->setLocale($language);

        $category = Category::where('slug',$slug)->firstOrFail();

        if (!$category)
            return response()->json(['error' => 'Category not found'], 404);
        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);

        $query->whereHas('category', function ($q) use ($category) {
            $q->where('id', $category->id);
        });

        // Handle sorting with validation
        $allowedSortFields = [
            'created_at',
            'daily_main_price',
            'weekly_main_price',
            'monthly_main_price',
            'passenger_capacity',
            'door_count'
        ];

        $sortField = in_array($request->input('sort_by'), $allowedSortFields)
            ? $request->input('sort_by')
            : 'created_at';

        $sortDirection = $request->input('sort_direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Handle pagination with validation
        $perPage = min(max($request->input('per_page', 100), 1), 100);

        return [
            'cars' =>  CarResource::collection($query->paginate($perPage)->withQueryString()),
            'category' => new DetailedCategoryResource($category)
        ];
    }

    public function getLocationCars(Request $request,$slug){
        $language = app()->getLocale()?? 'en';
        $location = Location::where('slug',$slug)->firstOrFail();
        $query = Car::with(['translations', 'images', 'color.translations', 'brand.translations', 'category.translations'])
            ->where('is_active', true);

        // Handle sorting with validation
        $allowedSortFields = [
            'created_at',
            'daily_main_price',
            'weekly_main_price',
            'monthly_main_price',
            'passenger_capacity',
            'door_count'
        ];

        $sortField = in_array($request->input('sort_by'), $allowedSortFields)
            ? $request->input('sort_by')
            : 'created_at';

        $sortDirection = $request->input('sort_direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Handle pagination with validation
        $perPage = min(max($request->input('per_page', 100), 1), 100);


        return [
            'cars' =>  CarResource::collection($query->paginate($perPage)->withQueryString()),
            'location' => new DetailedLocationResource($location)
        ];

    }

}
