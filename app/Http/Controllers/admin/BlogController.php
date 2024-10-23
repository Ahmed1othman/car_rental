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
        $this->translatableFields = ['title','content'];
        $this->nonTranslatableFields = ['is_active'];
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
        $this->validationRules = [
            'title.*' => 'required|string|max:255',
            'image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'content.*' => 'required|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
        ];

        $this->validationMessages = [

        ];
        parent::store($request);

    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $this->validationRules = [
            'title.*' => 'required|string|max:255',
            'image_path' => 'sometimes|mimes:jpg,jpeg,png,webp|max:4096',
            'content.*' => 'required|string',
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
