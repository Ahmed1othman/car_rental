<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class BLogController extends Controller
{
    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $homeData = $this->getHome($language);
        // Start with a base query
        $query = Blog::where('is_active',true)->with(['translations' => function ($query) use ($language) {
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

        // Return the results using a resource
        return [
            'section_title'=> $homeData->translations->first()->brand_section_title,
            'section_description'=> $homeData->translations->first()->brand_section_paragraph,
            'blogs'=> BlogResource::collection($brands)
        ];
    }
}
