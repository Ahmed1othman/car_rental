<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUsResource;
use App\Http\Resources\AdvertisementResource;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class AboutUsPageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language', 'en');
        $aboutData = $this->getAbout($language);

        return response()->json([
            'data' => new AboutUsResource($aboutData),
            'status' =>'success'
        ]);
    }
}
