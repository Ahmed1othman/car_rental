<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\DetailedBlogResource;
use App\Models\Blog;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use DBTrait;


    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        $homeData = $this->getHome($language);
        // Start with a base query
        $query = Blog::where('is_active',true);

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
            'section_title'=> $homeData->translations->first()->blog_section_title,
            'section_description'=> $homeData->translations->first()->blog_section_paragraph,
            'blogs'=> BlogResource::collection($brands)
        ];
    }

    public function show(Request $request ,$slug){

        $language = $request->header('Accept-Language') ?? 'en';

        $blog = Blog::whereHas('translations', function ($q) use ($slug) {
            $q->where('slug',$slug);
        })->firstOrFail();
        return new DetailedBlogResource($blog);
    }
}
