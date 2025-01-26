<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DetailedCategoryResource;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\DBTrait;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    use DBTrait;
    public function index(Request $request){
        // Start measuring a specific operation
        Debugbar::startMeasure('categories-fetch', 'Fetching Categories');
        
        $language = $request->header('Accept-Language') ?? 'en';
        
        // Add custom message to debugbar
        Debugbar::info('Fetching categories for language: ' . $language);
        
        // Cache key based on request parameters
        $cacheKey = "categories_{$language}_" . md5(json_encode($request->all()));
        
        $result = Cache::remember($cacheKey, 3600, function() use ($request, $language) {
            // Add debug information about cache miss
            Debugbar::info('Cache miss - fetching fresh data');
            
            $homeData = $this->getHome($language);
            
            // Start measuring database query time
            Debugbar::startMeasure('categories-query', 'Categories Database Query');
            
            $query = Category::where('is_active',true)->with(['translations' => function ($query) use ($language) {
                $query->where('locale', $language);
            }]);

            // Apply filters dynamically based on request query parameters

            // 1. Filter by brand name (translated)
            if ($request->has('filters')) {
                $filters = $request->input('filters');
                // Log filters being applied
                Debugbar::info('Applying filters:', $filters);
                
                foreach ($filters as $key => $value) {
                    if ($key == 'name'){
                        $query->whereHas('translations', function ($q) use ($value) {
                            $q->where('name', 'like', '%' . $value . '%');
                        });
                    }
                }
            }

            // Stop measuring database query time
            Debugbar::stopMeasure('categories-query');
            
            // 4. Pagination check
            if ($request->has('paginate') && $request->input('paginate') == 'true') {
                // User wants pagination
                $perPage = $request->input('per_page', 10); // Default to 10 if not provided
                $brands = $query->paginate($perPage);
                // Log pagination info
                Debugbar::info('Paginated results:', ['per_page' => $perPage, 'total' => $brands->total()]);
            } else {
                // No pagination, return all results
                $brands = $query->get();
                // Log total results
                Debugbar::info('Total results:', $brands->count());
            }

            return [
                'section_title'=> $homeData->translations->first()->category_section_title,
                'section_description'=> $homeData->translations->first()->category_section_paragraph,
                'categories'=> CategoryResource::collection($brands)
            ];
        });
        
        // Stop measuring the entire operation
        Debugbar::stopMeasure('categories-fetch');
        
        // Add memory usage information
        Debugbar::info('Memory Usage: ' . memory_get_usage(true) / 1024 / 1024 . ' MB');
        
        return $result;
    }

    public function show(Request $request, $slug)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        $category = Category::where('slug', $slug)->firstOrFail();
        return new DetailedCategoryResource($category);
    }
}
