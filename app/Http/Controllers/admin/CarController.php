<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\CarImage;
use App\Models\Category;
use App\Models\Color;
use App\Models\Feature;
use App\Models\Gear_type;
use Illuminate\Support\Facades\DB;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\Jobs\ProcessFileJob;
use App\Jobs\ProcessCarImages;

class CarController extends GenericController
{
    public function __construct()
    {
        parent::__construct('Car');
        
        $this->seo_question = true;
        $this->robots = true;
        $this->slugField = 'name';
        $this->uploadedfiles = ['media', 'default_image_path'];
        $this->translatableFields = ['name', 'description', 'long_description'];
        $this->nonTranslatableFields = [
            'brand_id', 'category_id', 'color_id', 'car_model_id', 'year_id', 'maker_id',
            'daily_main_price', 'daily_discount_price', 'weekly_main_price', 'weekly_discount_price',
            'monthly_main_price', 'monthly_discount_price', 'door_count', 'luggage_capacity',
            'passenger_capacity', 'status', 'gear_type_id', 'insurance_included', 'free_delivery',
            'is_featured', 'crypto_payment_accepted', 'is_flash_sale', 'only_on_afandina','is_active','period_ids'
        ];
    }

    public function index()
    {
        $request = request();
        $query = $this->model::query()->with(['brand', 'category', 'year', 'carModel', 'periods']);

        // تطبيق الفلاتر
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('period')) {
            $query->whereHas('periods', function($q) use ($request) {
                $q->where('period_id', $request->period);
            });
        }

        if ($request->filled('year')) {
            $query->where('year_id', $request->year);
        }

        if ($request->filled('model')) {
            $query->where('car_model_id', $request->model);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('translations', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%");
                });
            });
        }

        // جلب البيانات المطلوبة للفلتر
        $this->data['brands'] = Brand::with('carModels.translations')->get();
        $this->data['categories'] = Category::all();
        $this->data['periods'] = Period::all();
        $this->data['years'] = Year::all();
        
        // إضافة نتائج البحث
        $this->data['items'] = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('pages.admin.cars.index', $this->data);
    }

    // دالة لجلب الموديلات حسب الماركة
    public function getModelsByBrand($brandId)
    {
        $models = Car_model::where('brand_id', $brandId)
            ->with('translations')
            ->get()
            ->map(function($model) {
                $translation = $model->translations->where('locale', 'en')->first();
                $name = $translation ? $translation->name : ($model->translations->first() ? $model->translations->first()->name : 'N/A');
                return [
                    'id' => $model->id,
                    'name' => $name
                ];
            });
        
        return response()->json($models);
    }

    public function create()
    {
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = \App\Models\Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();
        $this->data['categories'] = \App\Models\Category::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        $this->data['periods'] = \App\Models\Period::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        $this->data['colors'] = \App\Models\Color::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();
        $this->data['years'] = \App\Models\Year::all();
        $this->data['gearTypes'] = \App\Models\Gear_type::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();
        $this->data['features'] = \App\Models\Feature::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        return parent::create();
    }

    public function edit($id)
    {
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = \App\Models\Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();
        $this->data['categories'] = \App\Models\Category::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        $this->data['periods'] = \App\Models\Period::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        $this->data['colors'] = \App\Models\Color::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();
        $this->data['years'] = \App\Models\Year::all();
        $this->data['features'] = \App\Models\Feature::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        $this->data['gearTypes'] = \App\Models\Feature::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->get();

        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        // Convert checkbox values to boolean
        $request->merge([
            'insurance_included' => $request->has('insurance_included'),
            'is_flash_sale' => $request->has('is_flash_sale'),
            'is_featured' => $request->has('is_featured'),
            'free_delivery' => $request->has('free_delivery'),
            'is_active' => $request->has('is_active'),
            'crypto_payment_accepted' => $request->has('crypto_payment_accepted'),
            'only_on_afandina' => $request->has('only_on_afandina'),
            'status' => $request->has('status') ? 'available' : 'not_available',
        ]);

        // Log the incoming request data for debugging
        \Log::info('Request Data:', $request->all());

        // Process translations data
        if ($request->has('translations')) {
            \Log::info('Translations Data:', $request->input('translations'));
            foreach ($request->input('translations') as $locale => $data) {
                foreach ($this->translatableFields as $field) {
                    if (isset($data[$field])) {
                        $request->merge([
                            "{$field}.{$locale}" => $data[$field]
                        ]);
                    }
                }
            }
        }

        // Set validation rules
        $this->validationRules = [
            'name.*' => 'required', 'string', 'max:255',
            'description.*' => 'nullable|string',
            'long_description.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'car_model_id' => 'nullable|exists:car_models,id',
            'year_id' => 'nullable|exists:years,id',
            'daily_main_price' => 'required|numeric|min:0',
            'daily_discount_price' => 'nullable|numeric|min:0|lt:daily_main_price',
            'weekly_main_price' => 'nullable|numeric|min:0',
            'weekly_discount_price' => 'nullable|numeric|min:0|lt:weekly_main_price',
            'monthly_main_price' => 'required|numeric|min:0',
            'monthly_discount_price' => 'nullable|numeric|min:0|lt:monthly_main_price',
            'door_count' => 'nullable|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'passenger_capacity' => 'nullable|integer|min:1',
            // 'status' => 'required|in:available,not_available',
            'color_id' => 'required|exists:colors,id',
            'gear_type_id' => 'required|exists:gear_types,id',
            'brand_id' => 'required|exists:brands,id',
            'year_id' => 'required|exists:years,id',
            'category_id' => 'required|exists:categories,id',
            'seo_questions.*.*.question' => 'nullable|string|max:255',
            'seo_questions.*.*.answer' => 'nullable|string|max:255',
            'default_image_path' => 'sometimes|nullable|image|mimes:jpeg,webp,png,jpg,gif|max:10048',
            'media.*' => 'somtimes|nullable|mimes:jpeg,webp,png,jpg,gif,svg,mp4,webm,ogg|max:102400',
            'insurance_included'=>'boolean',
            'is_flash_sale'=>'boolean',
            'is_featured'=>'boolean',
            'free_delivery'=>'boolean',
            'is_active'=>'boolean',
            'crypto_payment_accepted'=>'boolean',
            'only_on_afandina'=>'boolean',

            'status' => 'required|in:available,not_available',
        ];

        try {
            // Call parent update to handle common functionality
            $response = parent::update($request, $id);

            // Handle car-specific relationships
            $car = $this->model::findOrFail($id);

            // Handle features
            if ($request->has('features')) {
                $car->features()->sync($request->features);
            }

            // Update translations directly if needed
            if ($request->has('translations')) {
                foreach ($car->translations as $translation) {
                    $locale = $translation->locale;
                    $translationData = $request->input("translations.{$locale}");
                    
                    if ($translationData) {
                        foreach ($this->translatableFields as $field) {
                            if (isset($translationData[$field])) {
                                $translation->{$field} = $translationData[$field];
                            }
                        }
                        $translation->save();
                    }
                }
            }

            // Check if we need to generate descriptions for each translation
            foreach ($car->translations as $translation) {
                // Get the submitted data for this locale
                $requestData = $request->input('translations.' . $translation->locale, []);
                
                // Check if description was provided in the request
                $descriptionProvided = isset($requestData['description']) && !empty($requestData['description']);
                $longDescriptionProvided = isset($requestData['long_description']) && !empty($requestData['long_description']);
                
                // Get the current values from the database
                $currentDescription = $translation->description;
                $currentLongDescription = $translation->long_description;
                
                // Generate descriptions only if:
                // 1. No description was provided in the request AND
                // 2. No description exists in the database OR it's empty
                if ((!$descriptionProvided && empty($currentDescription)) || 
                    (!$longDescriptionProvided && empty($currentLongDescription))) {
                    \App\Jobs\GenerateCarDescriptions::dispatch($car, $translation->locale);
                }
            }


            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Car updated successfully',
                    'redirect' => route('admin.cars.index')
                ]);
            }

            return $response;

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the car: ' . $e->getMessage(),
                    'errors' => ['general' => [$e->getMessage()]]
                ], 500);
            }
            return back()->with('error', 'An error occurred while updating the car: ' . $e->getMessage())->withInput();
        }
    }

    public function store(Request $request)
{
    // Convert checkbox values to boolean
    $request->merge([
        'insurance_included' => $request->has('insurance_included'),
        'is_flash_sale' => $request->has('is_flash_sale'),
        'is_featured' => $request->has('is_featured'),
        'free_delivery' => $request->has('free_delivery'),
        'is_active' => $request->has('is_active'),
        'crypto_payment_accepted' => $request->has('crypto_payment_accepted'),
        'only_on_afandina' => $request->has('only_on_afandina'),
        'status' => $request->has('status') ? 'available' : 'not_available',
    ]);

    // Log the incoming request data for debugging
    \Log::info('Request Data:', $request->all());

    // Set validation rules
    $this->validationRules = [
        'name.*' =>'required', 'string', 'max:255',
        'description.*' => 'nullable|string',
        'long_description.*' => 'nullable|string',
        'meta_title.*' => 'nullable|string|max:255',
        'meta_description.*' => 'nullable|string',
        'meta_keywords.*' => 'nullable|string',
        'car_model_id' => 'nullable|exists:car_models,id',
        'year_id' => 'nullable|exists:years,id',
        'daily_main_price' => 'required|numeric|min:0',
        'daily_discount_price' => 'nullable|numeric|min:0|lt:daily_main_price',
        'weekly_main_price' => 'nullable|numeric|min:0',
        'weekly_discount_price' => 'nullable|numeric|min:0|lt:weekly_main_price',
        'monthly_main_price' => 'required|numeric|min:0',
        'monthly_discount_price' => 'nullable|numeric|min:0|lt:monthly_main_price',
        'door_count' => 'nullable|integer|min:1',
        'luggage_capacity' => 'nullable|integer|min:0',
        'passenger_capacity' => 'nullable|integer|min:1',
        // 'status' => 'required|in:available,not_available',
        'color_id' => 'required|exists:colors,id',
        'year_id' => 'required|exists:years,id',
        'gear_type_id' => 'required|exists:gear_types,id',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id',
        'seo_questions.*.*.question' => 'nullable|string|max:255',
        'seo_questions.*.*.answer' => 'nullable|string|max:255',
        'default_image_path' => 'required|mimes:jpeg,webp,png,jpg,gif,svg|max:10048',
        'media.*' => 'sometimes|nullable|mimes:jpeg,webp,png,jpg,gif,svg,mp4,webm,ogg|max:102400',
        'insurance_included'=>'boolean',
        'is_flash_sale'=>'boolean',
        'is_featured'=>'boolean',
        'free_delivery'=>'boolean',
        'is_active'=>'boolean',
        'crypto_payment_accepted'=>'boolean',
        'only_on_afandina'=>'boolean',
        'status' => 'required|in:available,not_available',
    ];

    try {
        // Validate the request
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        // Call parent store to handle common functionality
        $response = parent::store($request);

        // If response is redirect (success) and we have a car ID
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            // Get the newly created car
            $car = $this->model::latest()->first();

            // Handle features
            if ($request->has('features')) {
                $car->features()->sync($request->features);
            }

            // Check if we need to generate descriptions for each translation
            foreach ($car->translations as $translation) {
                // Only generate descriptions if they weren't provided by the user
                $requestData = $request->input('translations.' . $translation->locale, []);
                $description = $requestData['description'] ?? null;
                $longDescription = $requestData['long_description'] ?? null;
                
                if (empty($description) || empty($longDescription)) {
                    \App\Jobs\GenerateCarDescriptions::dispatch($car, $translation->locale);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Car created successfully',
                    'redirect' => route('admin.cars.index')
                ]);
            }

            return $response;
        }

        // If we get here, something went wrong in the parent store
        DB::rollback();
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create car',
                'errors' => $this->validator->errors()
            ], 422);
        }

        return $response;

    } catch (\Exception $e) {
        DB::rollback();
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the car: ' . $e->getMessage(),
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
        return back()->with('error', 'An error occurred while creating the car: ' . $e->getMessage())->withInput();
    }
}

    public function edit_images($id)
    {
        $this->data['item'] = Car::findOrFail($id);
        return view('pages.admin.' . $this->modelName . '.edit_images', $this->data);
    }

    public function deleteImage($id)
    {
        try {
            // Find the media record in the database by ID
            $media = CarImage::findOrFail($id);

            // Get the file path
            $filePath = $media->file_path;

            // Delete the database record first
            $media->delete();

            // Then try to delete the file if it exists
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json(['success' => true, 'message' => 'File deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting media: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            if (!$request->hasFile('files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files provided'
                ], 400);
            }

            $files = $request->file('files');
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                $mimeType = $file->getMimeType();
                $isVideo = str_starts_with($mimeType, 'video/');
                
                // Store file temporarily
                $tempPath = $file->store('temp');
                
                // Generate final path
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $isVideo ? '.' . $file->getClientOriginalExtension() : '.webp';
                $finalPath = 'media/' . $filename . '_' . uniqid() . $extension;
                
                if ($isVideo) {
                    // Move video file directly
                    Storage::disk('public')->put($finalPath, file_get_contents($file));
                    
                    // Create database record
                    $media = new CarImage();
                    $media->file_path = $finalPath;
                    $media->alt = $request->input('alt') ?? null;
                    $media->type = 'video';
                    $media->car_id = $car->id;
                    $media->save();
                    
                    // Clean up temp file
                    Storage::delete($tempPath);
                } else {
                    // Process image through job
                    ProcessFileJob::dispatch(
                        Car::class,
                        $car->id,
                        'media',
                        $tempPath,
                        $file->getClientOriginalName(),
                        [
                            'maxWidth' => 1920,
                            'maxHeight' => 1080,
                            'quality' => 95,
                            'alt' => $request->input('alt') ?? null,
                            'type' => 'image'
                        ],
                        true
                    );
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully. Processing will complete shortly.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error uploading files: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading files: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDefaultImage(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Store file temporarily
                $tempPath = $file->store('temp');
                
                // Generate final path
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $finalPath = 'images/' . $filename . '_' . uniqid() . '.webp';
                
                // Dispatch job to process file
                ProcessFileJob::dispatch(
                    Car::class,
                    $car->id,
                    'default_image_path',
                    $tempPath,
                    $file->getClientOriginalName(),
                    [
                        'maxWidth' => 1920,
                        'maxHeight' => 1080,
                        'quality' => 95
                    ],
                    false
                );
                
                return response()->json([
                    'success' => true,
                    'message' => 'Default image uploaded successfully. Processing will complete shortly.'
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
        try {
            $request->validate([
                'file_path' => 'required|array',
                'file_path.*' => 'required|image|mimes:jpeg,webp,png,jpg,gif,svg|max:10048',
                'car_id' => 'required|integer',
            ]);

            $car = Car::findOrFail($request->car_id);
            
            if ($request->hasFile('file_path')) {
                foreach ($request->file('file_path') as $file) {
                    // Store file temporarily
                    $tempPath = $file->store('temp');
                    
                    // Generate final path
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $finalPath = 'images/' . $filename . '_' . uniqid() . '.webp';
                    
                    // Dispatch job to process file
                    ProcessFileJob::dispatch(
                        Car::class,
                        $car->id,
                        'media',
                        $tempPath,
                        $file->getClientOriginalName(),
                        [
                            'maxWidth' => 1920,
                            'maxHeight' => 1080,
                            'quality' => 95,
                            'alt' => null
                        ],
                        true
                    );
                }
                
                return response()->json([
                    'message' => 'Images uploaded successfully. Processing will complete shortly.',
                    'status' => 'processing',
                    'car_id' => $car->id,
                    'total_images' => count($request->file('file_path'))
                ], 202);
            }

            return response()->json([
                'error' => 'No images provided',
                'status' => 'error'
            ], 400);

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
            
            if ($request->hasFile('default_image_path')) {
                $file = $request->file('default_image_path');
                
                // Store file temporarily
                $tempPath = $file->store('temp');
                
                // Generate final path
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $finalPath = 'images/' . $filename . '_' . uniqid() . '.webp';
                
                // Dispatch job to process file
                ProcessFileJob::dispatch(
                    Car::class,
                    $car->id,
                    'default_image_path',
                    $tempPath,
                    $file->getClientOriginalName(),
                    [
                        'maxWidth' => 1920,
                        'maxHeight' => 1080,
                        'quality' => 95
                    ],
                    false
                );
                
                return response()->json([
                    'message' => 'Image upload successful. Processing will complete shortly.',
                    'status' => 'processing',
                    'car_id' => $car->id
                ], 202);
            }

            return response()->json([
                'error' => 'No image file provided',
                'status' => 'error'
            ], 400);

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

    public function deleteMultipleImages(Request $request)
    {
        try {
            $imageIds = $request->input('image_ids', []);
            
            if (empty($imageIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images selected for deletion'
                ], 400);
            }

            $successCount = 0;
            $errors = [];

            foreach ($imageIds as $id) {
                try {
                    $media = CarImage::findOrFail($id);
                    $filePath = $media->file_path;

                    // Delete the database record
                    $media->delete();

                    // Delete the file if it exists
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to delete image ID {$id}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$successCount} images deleted successfully" . (count($errors) > 0 ? ". Errors: " . implode(', ', $errors) : "")
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting images: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSelectedImages(Request $request)
    {
        $request->validate([
            'mediaIds' => 'required|array',
            'mediaIds.*' => 'exists:car_images,id'
        ]);

        try {
            $images = CarImage::whereIn('id', $request->mediaIds)->get();
            
            foreach($images as $image) {
                // حذف الملف من التخزين
                if ($image->file_path && Storage::disk('public')->exists($image->file_path)) {
                    Storage::disk('public')->delete($image->file_path);
                }
                // حذف السجل من قاعدة البيانات
                $image->delete();
            }

            return response()->json(['success' => true, 'message' => 'Selected media deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting media: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAllImages($carId)
    {
        try {
            $images = CarImage::where('car_id', $carId)->get();
            
            foreach($images as $image) {
                // حذف الملف من التخزين
                if ($image->file_path && Storage::disk('public')->exists($image->file_path)) {
                    Storage::disk('public')->delete($image->file_path);
                }
                // حذف السجل من قاعدة البيانات
                $image->delete();
            }

            return response()->json(['success' => true, 'message' => 'All media deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting media: ' . $e->getMessage()], 500);
        }
    }
}
