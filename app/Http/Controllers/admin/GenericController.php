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

//        try {
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
                    if ($request->hasFile($fileField)) {
                        $data = $request->file($fileField);
                        // Check if data is an array (multiple images or videos)
                        if (is_array($data)) {
                            $filePaths = [];
                            foreach ($data as $item) {

                                $filename = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
                                $webpFilename = $filename . '_' . uniqid() . '.webp';

                                // Optimize, resize and convert the image to WebP
                                $imagePath = storage_path('app/public/images/' . $webpFilename);
                                $interventionImage = Image::make($item->getRealPath())
                                    ->encode('webp', 85) // 85 is the quality percentage
                                    ->save($imagePath);

                                    // Store image file path
                                    $filePaths[] = [
                                        'file_path' => 'images/'.$webpFilename,
                                        'alt' => $request->alt?? null,
                                        'type' => 'image'
                                    ];
                                    // Handle video URL
                                }
                            if ($request->has('youtube_links')){
                                if ($request->input('youtube_links')) {
                                    foreach ($request->input('youtube_links') as $link) {
                                        $url = $this->getYouTubeVideoId($link);
                                        $filePaths[] = [
                                            'file_path' => $url,
                                            'alt' => $request->alt?? null,
                                            'type' => 'video'
                                        ];
                                    }
                                }

                            }
                                // Attach the image or video paths to the template
                                $template->$fileField()->createMany($filePaths);
                            } else {
                            // Handle single file (e.g., 'logo' or single image)
                            if ($request->hasFile($fileField)) {
                                $file = $request->file($fileField);
                                $path = $file->store('images', 'public'); // Store single file

                                // Save path to the model directly
                                $template->$fileField = $path;
                                $template->save();
                            }
                        }

                    }
                }

            $this->exceptionsModelStore($request, $template);
            // Commit the transaction
            DB::commit();

            return redirect()->route('admin.' . $this->modelName . '.index')
                ->with('success', 'data added successfully');

//        } catch (\Exception $e) {
//            // Rollback the transaction if something goes wrong
//            DB::rollback();
////            return redirect()->back()->withErrors(['error' => 'An error occurred while saving the template.']);
//            return redirect()->back()->withErrors(['error' =>$e->getMessage() ]);
//        }
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


        foreach ($this->uploadedfiles as $fileField) {


            // Check if the field is plural (indicating a many-to-many relationship)
            if (Str::plural($fileField) === $fileField) {
                // Many-to-many relationship case (e.g., 'images' or 'documents')
                if (request()->has($fileField)) {
                    $galleryItems = request()->input($fileField);
                    $itemsToAttach = [];

                    foreach ($galleryItems as $item) {
                        if ($item['type'] === 'image' && isset($item['file_path'])) {
                            // Handle image file upload
                            $file = $item['file'];
                            $path = $file->store('images', 'public');
                            $itemsToAttach[] = [
                                'file_path' => $path,
                                'alt' => $item['alt'] ?? null,
                                'type' => 'image'
                            ];
                        } elseif ($item['type'] === 'video' && isset($item['file_path'])) {
                            // Handle video link
                            $itemsToAttach[] = [
                                'file_path' => $item['file_path'],
                                'alt' => $item['alt'] ?? null,
                                'type' => 'video'
                            ];
                        }
                    }

                    // Attach the items to the related model
                    $row->$fileField()->createMany($itemsToAttach);
                }
            }else {

                // Single field (file belongs directly to the same table, e.g., 'logo' or 'image')
                if (request()->hasFile($fileField)) {
                    $file = request()->file($fileField); // Get the single file

                    if ($row->$fileField) {
                        Storage::disk('public')->delete($row->$fileField);
                    }

                    // Store the file in a specific directory
                    $path = $file->store('images', 'public');

                    // Save the path directly in the table's column for this field (e.g., 'logo_path')
                    $row->$fileField = $path;
                    $row->save();
                }
            }
        }


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
                    ];

                    if ($this->robots) {
                        $robotsIndex = $validatedData['robots_index'][$langCode] ?? null;
                        $robotsFollow = $validatedData['robots_follow'][$langCode] ?? null;

                        $translatedData += [
                            'robots_index' => $robotsIndex === 'index' ? 'index' : 'noindex',
                            'robots_follow' => $robotsFollow === 'follow' ? 'follow' : 'nofollow',
                        ];
                    }


                    if ($this->modelName == "homes")
                        $translatedData['slug'] = Str::slug('home-'.$langCode);
                    else if ($this->modelName == "abouts")
                        $translatedData['slug'] = Str::slug('about-'.$langCode);
                    else
                        $translatedData['slug'] = Str::slug($validatedData[$this->slugField][$langCode].'-'.rand(1, 99999)??rand(1, 99999), '-');

                    // Update or create translations
                    $row->translations()->updateOrCreate(
                        ['locale' => $langCode],
                        $translatedData
                    );
                }
            }

            if ($this->isTranslatable)
            {
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
            }


        $this->exceptionsModelUpdate($request, $row);
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


    function getYouTubeVideoId($url) {
        $regExp = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&\n]{11})/';
        preg_match($regExp, $url, $matches);
        return $matches[1] ?? null; // Return the video ID or null
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
