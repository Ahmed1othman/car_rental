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

class CategoryController extends Controller
{
    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $homeData = $this->getHome($language);
        // Start with a base query
        $query = Category::where('is_active',true)->with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }]);

        // Apply filters dynamically based on request query parameters

        // 1. Filter by brand name (translated)
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $key => $value) {
                if ($key == 'name'){
                    $query->whereHas('translations', function ($q) use ($value) {
                        $q->where('name', 'like', '%' . $value . '%');
                    });
                }
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

        return [
            'section_title'=> $homeData->translations->first()->category_section_title,
            'section_description'=> $homeData->translations->first()->category_section_paragraph,
            'categories'=> CategoryResource::collection($brands)
        ];
    }

    public function show(Request $request, $slug)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        $category = Category::where('slug', $slug)->firstOrFail();
        return new DetailedCategoryResource($category);
    }
}
