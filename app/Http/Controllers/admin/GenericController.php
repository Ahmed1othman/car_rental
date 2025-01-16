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
use App\Jobs\ProcessFileJob;
use App\Traits\ImageProcessingTrait;

class GenericController extends Controller
{
    use ImageProcessingTrait;
    
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
        //merge the is_active field to request
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        foreach ($this->uploadedfiles as $fileField) {
            if ($request->has($fileField) && is_array($request->file($fileField))) {
                $this->validationRules[$fileField . '.*'] = 'nullable|mimes:jpg,jpeg,png,svg,webp,mp4,webm,ogg|max:102400'; // 100MB max
            } else {
                $this->validationRules[$fileField] = 'nullable|mimes:jpg,jpeg,png,svg,webp,mp4,webm,ogg|max:102400'; // 100MB max
            }
        }
        
        // Validate the request data
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);

        // Start a database transaction
        DB::beginTransaction();

        // try {
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
            $this->handleStoreSEOQuestions($validatedData, $row);

            DB::commit();
            return redirect()->route('admin.' . $this->modelName . '.index')->with('success', ucfirst($this->modelName) . ' created successfully.');

        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect()->back()->with('error', 'Error occurred while creating ' . $this->modelName . ': ' . $e->getMessage())->withInput();
        // }
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
            $this->handleUpdateSEOQuestions($validatedData, $row);


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
     * Handle storing new SEO questions
     * @param array $validatedData
     * @param $template
     * @return void
     */
    public function handleStoreSEOQuestions(array $validatedData, $template): void
    {
        foreach ($this->data['activeLanguages'] as $language) {
            $langCode = $language->code;
            if (isset($validatedData['seo_questions'][$langCode])) {
                foreach ($validatedData['seo_questions'][$langCode] as $seoQuestionData) {
                    $questionText = $seoQuestionData['question'] ?? '';
                    $answerText = $seoQuestionData['answer'] ?? '';
                    
                    // Only create if both question and answer are not empty
                    if (!empty(trim($questionText)) && !empty(trim($answerText))) {
                        $template->seoQuestions()->create([
                            'locale' => $langCode,
                            'question_text' => $questionText,
                            'answer_text' => $answerText,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Handle updating SEO questions by removing old ones and adding new ones
     * @param array $validatedData
     * @param $template
     * @return void
     */
    public function handleUpdateSEOQuestions(array $validatedData, $template): void
    {
        // First, remove all existing questions for this template
        $template->seoQuestions()->delete();
        
        // Then add the new questions
        foreach ($this->data['activeLanguages'] as $language) {
            $langCode = $language->code;
            if (isset($validatedData['seo_questions'][$langCode])) {
                foreach ($validatedData['seo_questions'][$langCode] as $seoQuestionData) {
                    $questionText = $seoQuestionData['question'] ?? '';
                    $answerText = $seoQuestionData['answer'] ?? '';
                    
                    // Only create if both question and answer are not empty
                    if (!empty(trim($questionText)) && !empty(trim($answerText))) {
                        $template->seoQuestions()->create([
                            'locale' => $langCode,
                            'question_text' => $questionText,
                            'answer_text' => $answerText,
                        ]);
                    }
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
        // Log the incoming validated data
        \Log::info('Validated Translation Data:', $validatedData);

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

            $translationData = array_merge($translationData, $metaData);

            // Debug the final data before save
            \Log::info('Translation Data:', $translationData);

            // Create or update translation
            $model->translations()->updateOrCreate(
                ['locale' => $langCode],
                $translationData
            );

            // Log the translation data before saving
            \Log::info('Saving Translation:', $model->translations()->where('locale', $langCode)->first()->toArray());

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
                        // Store file temporarily
                        $tempPath = $file->store('temp');
                        
                        // Dispatch job to process file
                        ProcessFileJob::dispatch(
                            get_class($model),
                            $model->id,
                            $fileField,
                            $tempPath,
                            $file->getClientOriginalName(),
                            [
                                'alt' => $request->alt ?? null,
                                'maxWidth' => 1920,
                                'maxHeight' => 1080,
                                'quality' => 95
                            ],
                            true
                        );
                    }
                } else {
                    // Store file temporarily
                    $tempPath = $files->store('temp');
                    
                    // Dispatch job to process file
                    ProcessFileJob::dispatch(
                        get_class($model),
                        $model->id,
                        $fileField,
                        $tempPath,
                        $files->getClientOriginalName(),
                        [
                            'maxWidth' => 1920,
                            'maxHeight' => 1080,
                            'quality' => 95
                        ],
                        false
                    );
                }
            }
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

        // if ($this->modelName == "cars") {

        //     if ($request->has('features')) {
        //         $features = $request->input('features');
        //         $template->features()->attach($features);
        //     }
        // }
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