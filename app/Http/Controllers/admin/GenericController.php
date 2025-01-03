<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Jobs\ProcessImageJob;

class GenericController extends Controller
{
    protected $model;
    protected $seo_question=true;
    protected $data = [];
    protected $modelName;
    public $validationRules = [];
    public $validationMessages = [];
    public $slugField = null; // Default to null if no slug is needed
    public $uploadedfiles = [];
    public $translatableFields = [];
    public $nonTranslatableFields = [];
    public string $defaultLocale;
    public bool $isTranslatable = true;
    public bool $robots = false;

    public function __construct($modelName)
    {
        $this->model = app("App\\Models\\" . ucfirst($modelName));
        $this->modelName = Str::plural(Str::snake($modelName));
        $this->data['model'] = $this->model ;
        $this->data['modelName'] = $this->modelName;
        $this->data['activeLanguages'] = Language::active()->get();
        $this->data['defaultLocale'] = 'en';
    }

    public function index()
    {
        if ($this->isTranslatable){
            $locale = $this->data['defaultLocale'];
            $this->data['items'] = $this->model::with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);}])->paginate(10);
        }else
            $this->data['items'] = $this->model::paginate(10);

        return view('pages.admin.' . $this->modelName . '.index', $this->data);
    }

    public function create()
    {
        $this->data['activeLanguages'] = Language::active()->get();
        return view('pages.admin.' . $this->modelName . '.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Add combined validation for images and videos
        foreach ($this->uploadedfiles as $fileField) {
            $this->validationRules[$fileField] = 'required|mimes:jpg,jpeg,png,svg,webp,mp4,webm,ogg|max:102400'; // 100MB max
        }
        
        // Validate the request data
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Store base data (non-translatable)
            $nonTranslatedData = [];
            foreach ($this->nonTranslatableFields as $nonTranslatableField) {
                if (isset($validatedData[$nonTranslatableField]))
                    $nonTranslatedData[$nonTranslatableField] = $validatedData[$nonTranslatableField] ?? null;
                elseif ($nonTranslatableField == "is_active") {
                    $nonTranslatedData[$nonTranslatableField] = $request->is_active;
                }
            }

            $row = $this->model::create($nonTranslatedData);

            // Handle translations
            $this->handleModelTranslations($validatedData, $row);

            // Handle file uploads (now supports both images and videos in same directory)
            $this->handleFileUpload($request, $row);

            // Handle SEO questions
            $this->handleSEOQuestionsForEachLanguage($validatedData, $row);

            DB::commit();
            return redirect()->route('admin.' . $this->modelName . '.index')->with('success', ucfirst($this->modelName) . ' created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error occurred while creating ' . $this->modelName . ': ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $this->data['item'] = $this->model::findOrFail($id);
        return view('pages.admin.' . $this->modelName . '.show', $this->data);
    }

    public function edit($id)
    {
        $this->data['item'] = $this->model::findOrFail($id);
        return view('pages.admin.' . $this->modelName . '.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        // Add combined validation for images and videos
        foreach ($this->uploadedfiles as $fileField) {
            $this->validationRules[$fileField] = 'sometimes|mimes:jpg,jpeg,png,svg,webp,mp4,webm,ogg|max:102400'; // 100MB max
        }

        // Validate the request data
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $row = $this->model::findOrFail($id);

            // Delete old file if exists and new file is uploaded
            foreach ($this->uploadedfiles as $fileField) {
                if ($request->hasFile($fileField) && $row->$fileField) {
                    Storage::disk('public')->delete($row->$fileField);
                }
            }

            // Update non-translatable fields
            foreach ($this->nonTranslatableFields as $field) {
                if (isset($validatedData[$field])) {
                    $row->{$field} = $validatedData[$field];
                }
            }
            $row->save();

            // Handle translations
            $this->handleModelTranslations($validatedData, $row, $id);

            // Handle file uploads (now supports both images and videos)
            $this->handleFileUpload($request, $row);

            // Handle SEO questions
            $this->handleSEOQuestionsForEachLanguage($validatedData, $row);

            DB::commit();
            return redirect()->route('admin.' . $this->modelName . '.index')->with('success', ucfirst($this->modelName) . ' updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error occurred while updating ' . $this->modelName . ': ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);

        foreach ($this->uploadedfiles as $fileField) {
            // Delete logo if exists
            if ($item->$fileField) {
                Storage::disk('public')->delete($item->$fileField);
            }
        }
        $item->delete();

        return redirect()->route('admin.' . $this->modelName . '.index')
            ->with('success', 'data is deleted successfully');
    }

    /**
     * @param array $validatedData
     * @param $template
     * @return void
     */
    public function handleSEOQuestionsForEachLanguage(array $validatedData, $template): void
    {
        foreach ($this->data['activeLanguages'] as $language) {
            $langCode = $language->code;
            if (isset($validatedData['seo_questions'][$langCode])) {
                foreach ($validatedData['seo_questions'][$langCode] as $seoQuestionData) {
                    $seoQuestion = $template->seoQuestions()->create([
                        'locale' => $langCode,
                        'question_text' => $seoQuestionData['question'] ?? '',
                        'answer_text' => $seoQuestionData['answer'] ?? null,
                    ]);
                }
            }
        }
    }

    /**
     * @param array $validatedData
     * @param $model
     * @param null $id
     * @return void
     */
    protected function handleModelTranslations($validatedData, $model, $id = null)
    {
        foreach ($this->data['activeLanguages'] as $language) {
            $langCode = $language->code;

            // Prepare translation data
            $translationData = [];
            foreach ($this->translatableFields as $field) {
                if (isset($validatedData[$field][$langCode])) {
                    $translationData[$field] = $validatedData[$field][$langCode];
                }
            }

            // Add meta data if present
            $metaData = [
                'locale' => $langCode,
                'meta_title' => $validatedData['meta_title'][$langCode] ?? null,
                'meta_description' => $validatedData['meta_description'][$langCode] ?? null,
                'meta_keywords' => $validatedData['meta_keywords'][$langCode] ?? null
            ];

            // Only generate slug for English locale
            if ($langCode === 'en' && $this->slugField) {
                // Generate base slug from the English field value
                $baseSlug = Str::slug($validatedData[$this->slugField][$langCode] ?? 'default-slug');
                
                // Check if slug exists
                $slug = $baseSlug;
                $counter = 1;
                
                while ($this->model::where('slug', $slug)
                    ->when($id, function ($query) use ($id) {
                        return $query->where('id', '!=', $id);
                    })
                    ->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                
                // Add the unique slug to the model data
                $model->slug = $slug;
                $model->save();
            }

            $translationData = array_merge($translationData, $metaData);

            // Create or update translation
            $model->translations()->updateOrCreate(
                ['locale' => $langCode],
                $translationData
            );
        }
    }

    protected function handleFileUpload($request, $model)
    {
        // Handle file uploads
        foreach ($this->uploadedfiles as $fileField) {
            if ($request->hasFile($fileField)) {
                $files = $request->file($fileField);
                
                // Check if it's multiple files
                if (is_array($files)) {
                    foreach ($files as $file) {
                        $this->processFile($file, $model, $fileField);
                    }
                } else {
                    $this->processFile($files, $model, $fileField);
                }
            }
        }
    }

    protected function processFile($file, $model, $fileField)
    {
        // Get file extension
        $extension = strtolower($file->getClientOriginalExtension());

        // Define allowed extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
        $videoExtensions = ['mp4', 'webm', 'ogg'];

        // Generate unique filename with original extension
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueFilename = $filename . '_' . uniqid();

        if (in_array($extension, $imageExtensions)) {
            // Process image
            $webpFilename = $uniqueFilename . '.webp';
            $imagePath = storage_path('app/public/images/' . $webpFilename);
            
            $image = Image::make($file->getRealPath());
            
            if ($this->modelName == "categories"){
                //width = 150 height = 88
                $image->resize(150, 88, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }else{
                // Get original aspect ratio
                $originalWidth = $image->width();
                $originalHeight = $image->height();
                
                // Calculate new dimensions maintaining aspect ratio
                $newHeight = 513;
                $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                
                $image->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp', 85)
                ->save($imagePath);

                // Save path to model
                $model->$fileField = 'images/' . $webpFilename;
                $model->save();
            }
        } elseif (in_array($extension, $videoExtensions)) {
            // Store video in images directory with original extension
            $finalFilename = $uniqueFilename . '.' . $extension;
            $filePath = $file->storeAs('images', $finalFilename, 'public');
            
            // Save path to model
            $model->$fileField = $filePath;
            $model->save();
        }
    }

    /**
     * @param Request $request
     * @param $template
     * @return void
     */
    public function exceptionsModelStore(Request $request, $template): void
    {
        if ($this->modelName == "blogs") {

            if ($request->has('cars')) {
                $cars = $request->input('cars');
                $template->cars()->attach($cars);
            }
        }

        if ($this->modelName == "cars") {

            if ($request->has('features')) {
                $features = $request->input('features');
                $template->features()->attach($features);
            }
        }
    }

    public function exceptionsModelUpdate(Request $request, $template): void
    {
        if ($this->modelName == "blogs") {

            if ($request->has('cars')) {
                $cars = $request->input('cars');
                $template->cars()->sync($cars);
            }
        }

        if ($this->modelName == "cars") {
            if ($request->has('features')) {
                $features = $request->input('features');
                $template->features()->sync($features);
            }
        }
    }
}
