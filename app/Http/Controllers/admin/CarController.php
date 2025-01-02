<?php
namespace App\Http\Controllers\admin;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\Color;
use App\Models\Feature;
use App\Models\Gear_type;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\Jobs\ProcessCarImage;
use App\Jobs\ProcessCarImages;

class CarController extends GenericController
{
    public function __construct()
    {
        parent::__construct('car');
        $this->seo_question =true;
        $this->robots =true;
        $this->slugField ='name';
        $this->translatableFields = ['name','description','long_description'];
        $this->uploadedfiles = ['default_image_path','images'];
        $this->nonTranslatableFields = [
            'daily_main_price',
            'daily_discount_price',
            'weekly_main_price',
            'weekly_discount_price',
            'monthly_main_price',
            'monthly_discount_price',
            'daily_mileage_included',
            'weekly_mileage_included',
            'monthly_mileage_included',
            'door_count',
            'luggage_capacity',
            'passenger_capacity',
            'insurance_included',
            'free_delivery',
            'is_featured',
            'crypto_payment_accepted',
            'is_flash_sale',
            'only_on_afandina',
            'show_in_home',
            'is_active',
            'status',
            'gear_type_id',
            'brand_id',
            'year_id',
            'color_id',
            'car_model_id',
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
        $this->data['features'] = Feature::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();

        $this->data['years'] = Year::get();
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

        $this->data['features'] = Feature::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();

        $this->data['years'] = Year::get();

        $car = Car::find($id);
        $this->data['carModels'] = Car_model::select('car_models.id', 'car_model_translations.name')
            ->leftJoin('car_model_translations', function($join) use ($locale) {
                $join->on('car_model_translations.car_model_id', '=', 'car_models.id')
                    ->where('car_model_translations.locale', '=', $locale);
            })
            ->where('brand_id', $car->brand_id)
            ->get();
        return parent::edit($id);
    }

    public function store(Request $request)
    {
        $request->merge([
            'insurance_included' => $request->has('insurance_included') ? true : false,
            'is_flash_sale' => $request->has('is_flash_sale') ? true : false,
            'is_featured' => $request->has('is_featured') ? true : false,
            'free_delivery' => $request->has('free_delivery') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'crypto_payment_accepted' => $request->has('crypto_payment_accepted') ? true : false,
            'only_on_afandina' => $request->has('only_on_afandina') ? true : false,

        ]);
        $this->validationRules = [
            'name.*' => [
                'required','string','max:255',
                function ($attribute, $value, $fail) {
                    // Similar logic as explained before
                    preg_match('/name\.(\w+)/', $attribute, $matches);
                    $locale = $matches[1] ?? null;

                    if ($locale) {
                        $exists = \App\Models\BrandTranslation::where('name', $value)
                            ->where('locale', $locale)
                            ->exists();

                        if ($exists) {
                            $fail("The name '{$value}' has already been taken for the locale '{$locale}'.");
                        }
                    }
                }],
            'description.*' => 'nullable|string',
            'long_description.*' => 'nullable|string',
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

            'daily_mileage_included' => 'nullable|numeric|min:0',
            'weekly_mileage_included' => 'nullable|numeric|min:0',
            'monthly_mileage_included' => 'nullable|numeric|min:0',
            'door_count' => 'nullable|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'passenger_capacity' => 'nullable|integer|min:1',
            'insurance_included' => 'boolean',
            'free_delivery' => 'boolean',
            'crypto_payment_accepted' => 'boolean',
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
            'car_model_id' => 'nullable|exists:car_models,id',
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
            'crypto_payment_accepted' => $request->has('crypto_payment_accepted') ? true : false,
            'only_on_afandina' => $request->has('only_on_afandina') ? true : false,

        ]);
        $this->validationRules = [
            'name.*' => [
                'required','string','max:255',
                function ($attribute, $value, $fail) use ($id) {
                    // Extract the locale from the field name (e.g., name.en, name.fr)
                    preg_match('/name\.(\w+)/', $attribute, $matches);
                    $locale = $matches[1] ?? null;

                    if ($locale) {
                        // Get the brand ID being updated from the request or route

                        // Check if a record with the same name and locale already exists
                        $exists = \App\Models\BrandTranslation::where('name', $value)
                            ->where('locale', $locale)
                            ->where('brand_id', '!=', $id) // Ignore the current brand's translation
                            ->exists();

                        if ($exists) {
                            $fail("The name '{$value}' has already been taken for the locale '{$locale}'.");
                        }
                    }
                },],
            'description.*' => 'nullable|string',
            'long_description.*' => 'nullable|string',
            'locale.*' => 'required|string|in:en,ar',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'car_model_id' => 'nullable|exists:car_models,id',
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

    public function uploadImage(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                
                foreach ($files as $file) {
                    // Generate unique filename
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $webpFilename = $filename . '_' . uniqid() . '.webp';
                    
                    // Define the correct storage paths
                    $publicPath = 'public/images/' . $webpFilename;
                    $fullPath = storage_path('app/' . $publicPath);
                    $relativePath = 'images/' . $webpFilename;

                    // Ensure directory exists
                    if (!file_exists(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0777, true);
                    }

                    // Process and optimize image
                    $image = Image::make($file->getRealPath());
                    
                    // Get original aspect ratio
                    $originalWidth = $image->width();
                    $originalHeight = $image->height();
                    
                    // Calculate new dimensions maintaining 200px height
                    $newHeight = 513;
                    $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                    
                    $image->resize($newWidth, $newHeight, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode('webp', 85)
                    ->save($fullPath);

                    // Create image relation
                    $car->images()->create([
                        'file_path' => $relativePath,
                        'alt' => null,
                        'type' => 'image'
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Images uploaded successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No images provided'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading images: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDefaultImage(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Generate unique filename
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $webpFilename = $filename . '_' . uniqid() . '.webp';
                
                // Define the correct storage paths
                $publicPath = 'public/images/' . $webpFilename;
                $fullPath = storage_path('app/' . $publicPath);
                $relativePath = 'images/' . $webpFilename;

                // Ensure directory exists
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0777, true);
                }

                // Process and optimize image
                $image = Image::make($file->getRealPath());
                
                // Get original aspect ratio
                $originalWidth = $image->width();
                $originalHeight = $image->height();
                
                // Calculate new dimensions maintaining 200px height
                $newHeight = 513;
                $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                
                $image->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp', 85)
                ->save($fullPath);

                // Delete old image if exists
                if ($car->image) {
                    Storage::disk('public')->delete($car->image);
                }
                
                // Update car with new image
                $car->default_image_path = $relativePath;
                $car->save();

                // Clear any cached images
                clearstatcache(true, public_path('storage/' . $relativePath));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Default image uploaded successfully',
                    'data' => [
                        'image_url' => asset('storage/' . $relativePath),
                        'image_path' => $relativePath
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image provided'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Error uploading default image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeImages(Request $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Request data:', $request->all());

        try {
            // Validate the request (Images should be passed as an array)
            $request->validate([
                'file_path' => 'required|array',
                'file_path.*' => 'required|image|mimes:jpeg,webp,png,jpg,gif,svg|max:10048',
                'car_id' => 'required|integer',
            ]);

            // Check if file_path field is present
            if (!$request->has('file_path')) {
                return response()->json(['error' => 'The file_path field is required.'], 400);
            }

            $car = Car::findOrFail($request->car_id);
            
            // Ensure temp directory exists
            if (!Storage::disk('public')->exists('temp')) {
                Storage::disk('public')->makeDirectory('temp');
            }

            // Ensure images directory exists
            if (!Storage::disk('public')->exists('images')) {
                Storage::disk('public')->makeDirectory('images');
            }

            $originalPaths = [];
            $finalPaths = [];
            
            // Store original images temporarily
            foreach ($request->file('file_path') as $image) {
                // Store original file temporarily
                $originalFilename = uniqid('temp_') . '.' . $image->getClientOriginalExtension();
                $originalPath = 'temp/' . $originalFilename;
                Storage::disk('public')->putFileAs('temp', $image, $originalFilename);
                $originalPaths[] = $originalPath;

                // Prepare final filename
                $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $webpFilename = $filename . '_' . uniqid() . '.webp';
                $finalPaths[] = 'images/' . $webpFilename;
            }

            // Dispatch the job to process all images
            ProcessCarImages::dispatch($car, $finalPaths, $originalPaths);

            return response()->json([
                'message' => 'Images uploaded successfully. Processing will complete shortly.',
                'status' => 'processing',
                'car_id' => $car->id,
                'total_images' => count($request->file('file_path'))
            ], 202);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Car not found',
                'status' => 'error'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'status' => 'error'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeImages: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Image processing failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function updateDefaultImage(Request $request)
    {
        try {
            $request->validate([
                'default_image_path' => 'required|image|mimes:jpeg,webp,png,jpg,gif,svg|max:10048',
                'car_id' => 'required|integer',
            ]);

            $car = Car::findOrFail($request->car_id);
            
            if ($car->default_image_path) {
                Storage::disk('public')->delete($car->default_image_path);
            }

            if (!$request->hasFile('default_image_path')) {
                return response()->json(['error' => 'No image file provided'], 400);
            }

            $image = $request->file('default_image_path');
            
            // Ensure temp directory exists
            if (!Storage::disk('public')->exists('temp')) {
                Storage::disk('public')->makeDirectory('temp');
            }
            
            // Store original file temporarily
            $originalFilename = uniqid('temp_') . '.' . $image->getClientOriginalExtension();
            $originalPath = 'temp/' . $originalFilename;
            Storage::disk('public')->putFileAs('temp', $image, $originalFilename);

            // Ensure images directory exists
            if (!Storage::disk('public')->exists('images')) {
                Storage::disk('public')->makeDirectory('images');
            }

            // Prepare final filename
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $webpFilename = $filename . '_' . uniqid() . '.webp';
            $finalPath = 'images/' . $webpFilename;

            // Dispatch the job to process the image
            ProcessCarImage::dispatch($car, $finalPath, $originalPath, true);

            return response()->json([
                'message' => 'Image upload successful. Processing will complete shortly.',
                'status' => 'processing',
                'car_id' => $car->id
            ], 202);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Car not found',
                'status' => 'error'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'status' => 'error'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in updateDefaultImage: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Image processing failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Check the status of image processing for a car
     */
    public function checkImageProcessingStatus($carId)
    {
        try {
            $car = Car::findOrFail($carId);
            
            // Check if there are any pending jobs for this car
            $pendingJobs = \DB::table('jobs')
                ->where('payload', 'like', '%"car_id":' . $carId . '%')
                ->count();

            // Get total processed images
            $processedImages = CarImage::where('car_id', $carId)->count();

            if ($pendingJobs > 0) {
                return response()->json([
                    'status' => 'processing',
                    'message' => 'Images are still being processed',
                    'processed_images' => $processedImages
                ]);
            }

            return response()->json([
                'status' => 'completed',
                'message' => 'All images have been processed',
                'total_images' => $processedImages,
                'images' => CarImage::where('car_id', $carId)
                    ->select('image_path')
                    ->get()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error checking status: ' . $e->getMessage()
            ], 500);
        }
    }
}
