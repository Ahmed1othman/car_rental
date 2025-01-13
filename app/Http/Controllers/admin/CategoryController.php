<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class CategoryController extends GenericController
{
    public function __construct()
    {
        parent::__construct('category');
        $this->seo_question =true;
        $this->robots =true;
        $this->slugField ='name';
        $this->translatableFields = ['name', 'title','description','article'];
        $this->nonTranslatableFields = ['is_active'];
        $this->uploadedfiles=['image_path'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,

        ]);
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'image_path' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'description.*' => 'required|string',
            'article.*' => 'nullable|string',
            'title.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'robots_index.*' => 'nullable',
            'robots_follow.*' => 'nullable',
            'is_active' => 'boolean',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,

        ]);
        // Define validation rules
        $this->validationRules = [
            'image_path' => 'sometimes|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'name.*' => 'required|string|max:255',
            'description.*' => 'required|string',
            'article.*' => 'nullable|string',
            'title.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'robots_index.*' => 'nullable',
            'robots_follow.*' => 'nullable',
            'is_active' => 'boolean',
            
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

}
