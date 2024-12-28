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
        
        // Validate the request data
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);
        
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Store base data (non-translatable)
            foreach ($this->nonTranslatableFields as $nonTranslatableField) {
                if (isset($validatedData[$nonTranslatableField]))
                    $nonTranslatedData[$nonTranslatableField] = $validatedData[$nonTranslatableField] ?? null;
                elseif ($nonTranslatableField == "is_active") {
                    $nonTranslatedData[$nonTranslatableField] = $request->is_active;
                }
            }
            $template = $this->model::create($nonTranslatedData);

            $this->handleModelTranslations($validatedData, $template);
            $this->handleSEOQuestionsForEachLanguage($validatedData, $template);

            // Handle file uploads
            foreach ($this->uploadedfiles as $fileField) {
                if ($request->hasFile($fileField)) {
                    $files = $request->file($fileField);
                    
                    // Check if it's multiple files
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            // Store file temporarily
                            $tempPath = $file->store('temp');
                            
                            // Process multiple files in background
                            ProcessImageJob::dispatch(
                                $tempPath,
                                $file->getClientOriginalName(),
                                get_class($template),
                                $template->id,
                                $fileField,
                                true
                            );
                        }
                    } else {
                        // Single file - process immediately
                        $file = $files;
                        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $webpFilename = $filename . '_' . uniqid() . '.webp';
                        
                        // Process and save image
                        $imagePath = storage_path('app/public/images/' . $webpFilename);
                        $image = Image::make($file->getRealPath());
                        
                        // Get original aspect ratio
                        $originalWidth = $image->width();
                        $originalHeight = $image->height();
                        
                        // Calculate new dimensions maintaining 200px height
                        $newHeight = 200;
                        $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                        
                        $image->resize($newWidth, $newHeight, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->encode('webp', 85)
                        ->save($imagePath);

                        // Save path to model
                        $template->$fileField = 'images/' . $webpFilename;
                        $template->save();
                    }
                }
            }

            $this->exceptionsModelStore($request, $template);
            DB::commit();

            return redirect()->route('admin.' . $this->modelName . '.index')
                ->with('success', 'Data added successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
        $request->merge([
            'is_active' => $request->has('is_active') ? $request->is_active : false,
        ]);

        // Validate the request data
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);

        DB::beginTransaction();
        try {
            $row = $this->model::findOrFail($id);

            // Update non-translatable fields
            foreach ($this->nonTranslatableFields as $field) {
                if (isset($request->$field))
                    $row->$field = $request->$field ?? null;
            }
            $row->save();

            // Handle file uploads
            foreach ($this->uploadedfiles as $fileField) {
                if ($request->hasFile($fileField)) {
                    $files = $request->file($fileField);
                    
                    // Check if it's multiple files
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            // Store file temporarily
                            $tempPath = $file->store('temp');
                            
                            // Process multiple files in background
                            ProcessImageJob::dispatch(
                                $tempPath,
                                $file->getClientOriginalName(),
                                get_class($row),
                                $row->id,
                                $fileField,
                                true
                            );
                        }
                    } else {
                        // Single file - process immediately
                        $file = $files;
                        
                        // Delete old file if exists
                        if ($row->$fileField) {
                            Storage::disk('public')->delete($row->$fileField);
                        }

                        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $webpFilename = $filename . '_' . uniqid() . '.webp';
                        
                        // Process and save image
                        $imagePath = storage_path('app/public/images/' . $webpFilename);
                        Image::make($file->getRealPath())
                            ->resize(1200, 1200, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('webp', 85)
                            ->save($imagePath);

                        // Save path to model
                        $row->$fileField = 'images/' . $webpFilename;
                        $row->save();
                    }
                }
            }

            if ($this->translatableFields) {
                // Handle translations
                foreach ($this->data['activeLanguages'] as $language) {
                    $langCode = $language->code;
                    foreach ($this->translatableFields as $translatableField) {
                        $translated[$translatableField] = $validatedData[$translatableField][$langCode] ?? null;
                    }

                    $translatedData = $translated + [
                        'meta_title' => $validatedData['meta_title'][$langCode] ?? null,
                        'meta_description' => $validatedData['meta_description'][$langCode] ?? null,
                        'meta_keywords' => $validatedData['meta_keywords'][$langCode] ?? null,
                    ];

                    if ($this->robots) {
                        $robotsIndex = $validatedData['robots_index'][$langCode] ?? null;
                        $robotsFollow = $validatedData['robots_follow'][$langCode] ?? null;

                        $translatedData += [
                            'robots_index' => $robotsIndex === 'index' ? 'index' : 'noindex',
                            'robots_follow' => $robotsFollow === 'follow' ? 'follow' : 'nofollow',
                        ];
                    }

                    // Handle slug
                    if ($this->modelName == "homes")
                        $translatedData['slug'] = Str::slug('home-'.$langCode);
                    else if ($this->modelName == "abouts")
                        $translatedData['slug'] = Str::slug('about-'.$langCode);
                    else
                        $translatedData['slug'] = Str::slug(
                            ($validatedData[$this->slugField][$langCode] ?? 'default-slug') . '-' . rand(1, 99999),
                            '-'
                        );

                    // Update translations
                    $row->translations()->updateOrCreate(
                        ['locale' => $langCode],
                        $translatedData
                    );
                }
            }

            $this->exceptionsModelUpdate($request, $row);
            DB::commit();

            return redirect()->route('admin.' . $this->modelName . '.index')
                ->with('success', 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
     * @param $template
     * @return void
     */
    public function handleModelTranslations(array $validatedData, $template): void
    {
        if ($this->translatableFields) {
            // Handle translations for each active language
            foreach ($this->data['activeLanguages'] as $language) {
                $langCode = $language->code;
                $translatedData = [];
                foreach ($this->translatableFields as $translatableField) {
                    $translatedData[$translatableField] = $validatedData[$translatableField][$langCode] ?? null;
                }

                $metaData = [
                    'locale' => $langCode,
                    'meta_title' => $validatedData['meta_title'][$langCode] ?? null,
                    'meta_description' => $validatedData['meta_description'][$langCode] ?? null,
                    'meta_keywords' => $validatedData['meta_keywords'][$langCode] ?? null,
                    'slug' => Str::slug(
                        ($validatedData[$this->slugField][$langCode] ?? 'default-slug') . '-' . rand(1, 99999),
                        '-'
                    )
                ];

                if ($this->robots) {
                    $robotsIndex = $validatedData['robots_index'][$langCode] ?? null;
                    $robotsFollow = $validatedData['robots_follow'][$langCode] ?? null;

                    $metaData += [
                        'robots_index' => $robotsIndex === 'index' ? 'index' : 'noindex',
                        'robots_follow' => $robotsFollow === 'follow' ? 'follow' : 'nofollow',
                    ];
                }

                $template->translations()->create(
                    $translatedData + $metaData
                );
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
