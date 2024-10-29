<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUsResource;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\ContactUsResource;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Traits\DBTrait;
use Illuminate\Http\Request;

class ContactUsPageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language', 'en');
        $contactData = $this->getAbout($language);
        $homeData = $this->gethome($language);

        $faqs = FAQ::where('is_active',1)->get();

        return response()->json([
            'data' => new ContactUsResource([$homeData,$contactData,$faqs]),
            'status' =>'success'
        ]);
    }
}
