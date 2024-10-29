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
use Illuminate\Support\Fluent;

class ContactUsPageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language', 'en');
        $contactData = $this->getContact();
        $homeData = $this->gethome($language);

        $faqs = FAQ::where('is_active',1)->get();

        $data = new Fluent([
            'contactData' => $contactData,
            'homeData' => $homeData,
            'faqs' => $faqs,
        ]);
        return response()->json([
            'data' => new ContactUsResource($data),
            'status' =>'success'
        ]);
    }
}
