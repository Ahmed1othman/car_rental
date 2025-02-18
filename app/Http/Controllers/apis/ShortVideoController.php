<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DetailedShortVideoResource;
use App\Http\Resources\ShortVideoResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Short_video;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class ShortVideoController extends Controller
{
    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        // Start with a base query
        $query = Short_video::where('is_active',true)->with(['translations' => function ($query) use ($language) {
            $query->where('locale', $language);
        }]);

        // Apply filters dynamically based on request query parameters

        // 1. Filter by brand name (translated)
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $key => $value) {
                if ($key == 'title'){
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
            'short_videos'=> ShortVideoResource::collection($brands)
        ];
    }

    public function show(Request $request, $slug)
    {
        $video = Short_video::where('slug', $slug)->firstOrFail();
        return new DetailedShortVideoResource($video);
    }
}
