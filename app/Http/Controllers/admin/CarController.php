<?php
namespace App\Http\Controllers\admin;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\Color;
use App\Models\Gear_type;
use App\Models\Maker;
use App\Models\old\BodyStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends GenericController
{
    public function __construct()
    {
        parent::__construct('car');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name','description'];
        $this->uploadedfiles = ['default_image_path','images'];
        $this->nonTranslatableFields = [
            'daily_main_price',
            'daily_discount_price',
            'weekly_main_price',
            'weekly_discount_price',
            'monthly_main_price',
            'monthly_discount_price',
            'door_count',
            'luggage_capacity',
            'passenger_capacity',
            'insurance_included',
            'free_delivery',
            'is_featured',
            'is_flash_sale',
            'only_on_afandina',
            'show_in_home',
            'is_active',
            'status',
            'gear_type_id',
            'brand_id',
            'color_id',
            'category_id',
        ];
    }

    public function create()
    {
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['categories'] = Category::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['gearTypes'] = Gear_type::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['colors'] = Color::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();

        return parent::create();
    }


    public function edit($id){
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['categories'] = Category::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['gearTypes'] = Gear_type::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        $this->data['colors'] = Color::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();

        return parent::edit($id);
    }

    public function store(Request $request)
    {
        return $request->all();
        $request->merge([
            'insurance_included' => $request->has('insurance_included') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
            'free_delivery' => $request->has('free_delivery') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'only_on_afandina' => $request->has('only_on_afandina') ? true : false,
        ]);
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'locale.*' => 'required|string|in:en,ar',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'slug' => 'nullable|string|unique:table_name,slug',
//            'car_id' => 'required|exists:cars,id',
            'daily_main_price' => 'required|numeric|min:0',
            'daily_discount_price' => 'nullable|numeric|min:0|lt:daily_main_price',
            'weekly_main_price' => 'nullable|numeric|min:0',
            'weekly_discount_price' => 'nullable|numeric|min:0|lt:weekly_main_price',
            'monthly_main_price' => 'required|numeric|min:0',
            'monthly_discount_price' => 'nullable|numeric|min:0|lt:monthly_main_price',
            'door_count' => 'nullable|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'passenger_capacity' => 'nullable|integer|min:1',
            'insurance_included' => 'boolean',
            'free_delivery' => 'boolean',
            'is_featured' => 'boolean',
            'is_flash_sale' => 'boolean',
            'is_active' => 'boolean',
            'show_in_home' => 'boolean',
            'only_on_afandina' => 'boolean',
            'status' => 'required|in:available,not_available',
            'gear_type_id' => 'required|exists:gear_types,id',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
//            'body_style_id' => 'nullable|exists:body_styles,id',
            'maker_id' => 'nullable|exists:makers,id',
            'default_image_path' => 'nullable',
            'seo_questions.*.*.question' => 'nullable|string|max:255',
            'seo_questions.*.*.answer' => 'nullable|string|max:255',
        ];


        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'insurance_included' => $request->has('insurance_included') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
            'free_delivery' => $request->has('free_delivery') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'only_on_afandina' => $request->has('only_on_afandina') ? true : false,
        ]);
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'locale.*' => 'required|string|in:en,ar',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'slug' => 'nullable|string|unique:table_name,slug',
            'daily_main_price' => 'required|numeric|min:0',
            'daily_discount_price' => 'nullable|numeric|min:0|lt:daily_main_price',
            'weekly_main_price' => 'nullable|numeric|min:0',
            'weekly_discount_price' => 'nullable|numeric|min:0|lt:weekly_main_price',
            'monthly_main_price' => 'required|numeric|min:0',
            'monthly_discount_price' => 'nullable|numeric|min:0|lt:monthly_main_price',
            'door_count' => 'nullable|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'passenger_capacity' => 'nullable|integer|min:1',
            'insurance_included' => 'boolean',
            'free_delivery' => 'boolean',
            'is_featured' => 'boolean',
            'is_flash_sale' => 'boolean',
            'color_id' => 'required|exists:colors,id',
            'only_on_afandina' => 'boolean',
            'is_active' => 'boolean',
            'show_in_home'=> 'boolean',
            'status' => 'required|in:available,not_available',
            'gear_type_id' => 'required|exists:gear_types,id',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
//            'body_style_id' => 'nullable|exists:body_styles,id',
//            'maker_id' => 'nullable|exists:makers,id',
            'default_image_id' => 'nullable|exists:images,id',
            'seo_questions.*.*.question' => 'nullable|string|max:255',
            'seo_questions.*.*.answer' => 'nullable|string|max:255',
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

    public function edit_images($id)
    {
        $this->data['item'] = Car::findOrFail($id);
        return view('pages.admin.' . $this->modelName . '.edit_images', $this->data);
    }

    public function deleteImage($id)
    {
        // Find the media record in the database by ID
        $media = CarImage::find($id);

        if ($media) {
            if ($media->type=="image")
                Storage::disk('public')->delete($media->file_path);

            // Optionally delete the database record
            $media->delete();

            return response()->json(['message' => 'Image deleted successfully'], 200);
        }

        return response()->json(['message' => 'Image not found'], 404);
    }

    public function storeYoutubeUrls(Request $request)
    {
        // Validate the request (URLs should be passed as an array)
        $request->validate([
            'youtube_urls' => 'required|array',
            'youtube_urls.*' => 'required',
            'car_id' => 'required|integer',
        ]);
        // Loop through the URLs and store them in the database
        foreach ($request->input('youtube_urls') as $url) {

            $media = new CarImage();
            $media->file_path = $this->getYouTubeVideoId($url);
            $media->alt = $request->input('alt')??null; // Assume alt text is also passed as an array
            $media->type = 'video';
            $media->car_id = $request->input('car_id');
            $media->save();
        }

        return response()->json(['message' => 'YouTube URLs stored successfully'], 200);
    }


    public function storeImages(Request $request)
    {
        // Validate the request (Images should be passed as an array)
        $request->validate([
            'file_path' => 'required|array',
            'file_path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'alt' => 'nullable|string|max:255',
            'car_id' => 'required|integer',
        ]);

        // Loop through the images and store them
        foreach ($request->file('file_path') as $image) {
            // Store the image
            $imagePath = $image->store('images', 'public'); // Saves in 'storage/app/public/images'

            // Save the path and other data in the database
            $media = new CarImage();
            $media->file_path = $imagePath;
            $media->alt = $request->input('alt')??null;// Assume alt text is also passed as an array
            $media->type = 'image';
            $media->car_id = $request->input('car_id');
            $media->save();
        }

        return response()->json(['message' => 'Images uploaded successfully'], 200);
    }

    public function updateDefaultImage(Request $request)
    {


        // Validate the request (Images should be passed as an array)
        $request->validate([
            'default_image_path' => 'required',
            'car_id' => 'required|integer',
        ]);
        $car= Car::find($request->car_id);
        Storage::disk('public')->delete($car->default_image_path);
        $image= $request->file('default_image_path');
        $imagePath = $image->store('images', 'public');
        $car->update(['default_image_path'=>$imagePath]);
        return response()->json(['message' => 'Default Image Updated successfully'], 200);
    }

}
