<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\DetailedServiceResource;
use App\Models\Service;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';

        $homeData = $this->getHome($language);
        // Start with a base query
        $query = Service::where('is_active',true)->with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }]);
        // 1. Filter by brand name (translated)
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $key => $value) {
                if ($key == 'show_in_home')
                    $query->where('show_in_home', $value);
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
            'section_title'=> $homeData->translations->first()->why_choose_us_section_title,
            'section_description'=> $homeData->translations->first()->why_choose_us_section_paragraph,
            'services'=> ServiceResource::collection($rows)
        ];
    }

    public function show(Request $request, $slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return new DetailedServiceResource($service);
    }
}
