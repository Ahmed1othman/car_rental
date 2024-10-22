<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        return $request->all();
        $language = $request->header('Accept-Language') ?? 'en';

        $homeData = $this->getHome($language);
        // Start with a base query
        $query = Location::where('is_active',true)->with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }]);
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
        if ($request->has('paginate') && $request->input('paginate') == true) {
            // User wants pagination
            $perPage = $request->input('per_page', 10); // Default to 10 if not provided
            $rows = $query->paginate($perPage);
        } else {
            // No pagination, return all results
            $rows = $query->get();
        }

        return [
            'section_title'=> $homeData->where_find_us_section_title,
            'section_description'=> $homeData->where_find_us_section_paragraph,
            'locations'=> LocationResource::collection($rows)
        ];
    }
}
