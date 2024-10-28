<?php
namespace App\Http\Controllers\admin;
use App\Models\Car;
use Illuminate\Http\Request;

class BlogController extends GenericController
{
    public function __construct()
    {
        parent::__construct('blog');
        $this->seo_question =true;
        $this->slugField ='title';
        $this->translatableFields = ['title','content','description'];
        $this->nonTranslatableFields = ['is_active','show_in_home'];
        $this->uploadedfiles = ['image_path'];
    }

    public function create()
    {
        $locale = $this->data['defaultLocale'];
        $this->data['cars'] = Car::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        return parent::create();
    }

    public function edit($id){
        $locale = $this->data['defaultLocale'];
        $this->data['cars'] = Car::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        return parent::edit($id);
    }

    public function store(Request $request)
    {
        $request->merge([
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        $this->validationRules = [
            'title.*' => 'required|string|max:255',
            'cars' => 'nullable|array',
            'image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'content.*' => 'required|string',
            'description.*' => 'required|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        // Define validation rules
        $this->validationRules = [
            'title.*' => 'required|string|max:255',
            'cars' => 'nullable|array',
            'image_path' => 'sometimes|mimes:jpg,jpeg,png,webp|max:4096',
            'content.*' => 'required|string',
            'description.*' => 'required|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

}
