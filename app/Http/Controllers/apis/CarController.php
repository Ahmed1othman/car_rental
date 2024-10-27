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
