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
        
        $cacheKey = "categories_{$language}_" . md5(json_encode($request->all()));
        
        $result = Cache::remember($cacheKey, 3600, function() use ($request, $language) {
            $homeData = $this->getHome($language);
            
            $query = Category::where('is_active',true)->with(['translations' => function ($query) use ($language) {
                $query->where('locale', $language);
            }]);

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

            if ($request->has('paginate') && $request->input('paginate') == 'true') {
                $perPage = $request->input('per_page', 10); 
                $brands = $query->paginate($perPage);
            } else {
                $brands = $query->get();
            }

            return [
                'section_title'=> $homeData->translations->first()->category_section_title,
                'section_description'=> $homeData->translations->first()->category_section_paragraph,
                'categories'=> CategoryResource::collection($brands)
            ];
        });
        
        return $result;
    }

    public function show(Request $request, $slug)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        $category = Category::where('slug', $slug)->firstOrFail();
        return new DetailedCategoryResource($category);
    }
}
