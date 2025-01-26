<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DetailedCategoryResource;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';

        // Generate a cache key based on the request parameters
        $cacheKey = 'categories_' . $language . '_' . json_encode($request->all());

        // Try to get data from cache first
        $cachedData = Cache::get($cacheKey);
        if (!$cachedData) {
            // Start with a base query
            $query = Category::where('is_active',true)
                ->with(['translations' => function ($query) use ($language) {
                    $query->where('locale', $language);
                }]);

            // Apply filters if any
            if ($request->has('filters')) {
                $filters = $request->input('filters');
                foreach ($filters as $key => $value) {
                    if ($key == 'show_in_home')
                        $query->where($key,true);
                    if ($key == 'name'){
                        $query->whereHas('translations', function ($q) use ($value) {
                            $q->where('name', 'like', '%' . $value . '%');
                        });
                    }
                }
            }

            // Check for pagination
            if ($request->has('paginate') && $request->input('paginate') == 'true') {
                $perPage = $request->input('per_page', 10);
                $brands = $query->paginate($perPage);
                $data = CategoryResource::collection($brands);
            } else {
                $brands = $query->get();
                $data = CategoryResource::collection($brands);
            }

            // Cache the results for 1 hour
            Cache::put($cacheKey, $data, now()->addHour());
        } else {
            $data = $cachedData;
        }

        return [
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => $data
        ];
    }

    public function show(Request $request, $slug)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        $category = Category::where('slug', $slug)->firstOrFail();
        return new DetailedCategoryResource($category);
    }
}
