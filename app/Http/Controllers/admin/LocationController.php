<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class LocationController extends GenericController
{
    public function __construct()
    {
        parent::__construct('location');
        $this->seo_question =true;
        $this->robots =true;
        $this->slugField ='name';
        $this->translatableFields = ['name','title','description','article'];
        $this->nonTranslatableFields = ['is_active'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'title.*' => 'nullable|string',
            'article.*' => 'nullable|string',
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
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'title.*' => 'nullable|string',
            'article.*' => 'nullable|string',
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
