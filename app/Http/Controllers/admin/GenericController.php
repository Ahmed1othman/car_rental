<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenericController extends Controller
{
    protected $model;
    protected $seo_question=false;
    protected $data = [];
    protected $modelName;
    public $validationRules = [];
    public $validationMessages = [];
    public $slugField = null; // Default to null if no slug is needed
    public $uploadedfiles = [];
    public $translatableFields = [];
    public $nonTranslatableFields = [];
    public string $defaultLocale;

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
        $locale = $this->data['defaultLocale'];
        $this->data['items'] = $this->model::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->paginate(10);
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
            // Store the template base data (non-translatable)
        foreach ($this->nonTranslatableFields as $nonTranslatableField) {
            if (isset($validatedData[$nonTranslatableField]))
                $nonTranslatedData[$nonTranslatableField] = $validatedData[$nonTranslatableField]?? null;
            elseif ($nonTranslatableField == "is_active"){
                $nonTranslatedData[$nonTranslatableField] = $request->is_active;
            }
        }
        $template = $this->model::create($nonTranslatedData);

            $this->handleModelTranslations($validatedData, $template);

            // Handle SEO questions for each language
            $this->handleSEOQuestionsForEachLanguage($validatedData, $template);


            foreach ($this->uploadedfiles as $fileField) {

                // Check if the field is plural (indicating a many-to-many relationship)
                if (Str::plural($fileField) === $fileField) {

                    // Many-to-many relationship case (e.g., 'images' or 'documents')
                    if (request()->hasFile($fileField)) {
                        $files = request()->file($fileField); // Get the uploaded files
                        $filePaths = [];

                        foreach ($files as $file) {
                            // Store each file and get its path
                            $path = $file->store('images', 'public');
                            $filePaths[] = ['file_path' => $path]; // Collect the file paths for attaching later
                        }

                        // Assuming a many-to-many relation like 'files'
                        // Attach the file paths to the related model (adjust the relationship name)
                        $this->model->$fileField()->createMany($filePaths);
                    }

                } else {

                    // Single field (file belongs directly to the same table, e.g., 'logo' or 'image')
                    if (request()->hasFile($fileField)) {
                        $file = request()->file($fileField); // Get the single file

                        // Store the file in a specific directory
                        $path = $file->store('images', 'public');

                        // Save the path directly in the table's column for this field (e.g., 'logo_path')
                        $template->$fileField = $path;
                        $template->save();
                    }
                }
            }
            // Commit the transaction
            DB::commit();

            return redirect()->route('admin.' . $this->modelName . '.index')
                ->with('success', 'data added successfully');

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();
//            return redirect()->back()->withErrors(['error' => 'An error occurred while saving the template.']);
            return redirect()->back()->withErrors(['error' =>$e->getMessage() ]);
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
//        try {
            // Find the template to be updated
            $row = $this->model::findOrFail($id);


            // Update non-translatable fields
            foreach ($this->nonTranslatableFields as $field) {
                echo $field;
                if (isset($request->$field))
                    $row->$field = $request->$field ?? null;
            }
            $row->save();

            if($this->translatableFields) {
                // Update translated fields for each language
                foreach ($this->data['activeLanguages'] as $language) {
                    $langCode = $language->code;
                    foreach ($this->translatableFields as $translatableField) {
                        $translated[$translatableField] = $validatedData[$translatableField][$langCode] ?? null;
                    }

                    $translatedData = $translated + [
                        'meta_title' => $validatedData['meta_title'][$langCode] ?? null,
                        'meta_description' => $validatedData['meta_description'][$langCode] ?? null,
                        'meta_keywords' => $validatedData['meta_keywords'][$langCode] ?? null,
                        'slug' => Str::slug($validatedData[$this->slugField][$langCode], '-')
                    ];

                    // Update or create translations
                    $row->translations()->updateOrCreate(
                        ['locale' => $langCode],
                        $translatedData
                    );
                }
            }

            if ($this->seo_question) {
                // Handle SEO questions for each language
                foreach ($this->data['activeLanguages'] as $language) {
                    $langCode = $language->code;

                    // Get all existing SEO questions for this template and language
                    $existingQuestions = $row->seoQuestions()->where('locale', $langCode)->get();
                    $submittedQuestions = $validatedData['seo_questions'][$langCode] ?? [];
                    // Track submitted question IDs
                    $submittedIds = array_column($submittedQuestions, 'id', 'id');
                    // Delete questions not submitted
                    foreach ($existingQuestions as $existingQuestion) {
                        if (!isset($submittedIds[$existingQuestion->id])) {
                            $existingQuestion->delete();
                        }
                    }

                    // Update or create questions
                    foreach ($submittedQuestions as $seoQuestionData) {
                        $row->seoQuestions()->updateOrCreate(
                            [
                                'id' => $seoQuestionData['id'] ?? null, // Use existing ID if available
                                'locale' => $langCode,
                            ],
                            [
                                'question_text' => $seoQuestionData['question'] ?? '',
                                'answer_text' => $seoQuestionData['answer'] ?? null,
                            ]
                        );
                    }
                }
            }
            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.' . $this->modelName . '.index')
                ->with('success', 'row updated successfully');
//        } catch (\Exception $e) {
//            // Rollback in case of an error
//            DB::rollback();
//            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
//        }
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
                foreach ($this->translatableFields as $translatableField) {
                    $translatedData[$translatableField] = $validatedData[$translatableField][$langCode] ?? null;
                }

                $template->translations()->create(
                    $translatedData +
                    [
                    'locale' => $langCode,
                    'name' => $validatedData['name'][$langCode],
                    'meta_title' => $validatedData['meta_title'][$langCode] ?? null,
                    'meta_description' => $validatedData['meta_description'][$langCode] ?? null,
                    'meta_keywords' => $validatedData['meta_keywords'][$langCode] ?? null,
                    'slug' => Str::slug($validatedData[$this->slugField][$langCode], '-')
                ]);
            }
        }
    }
}
