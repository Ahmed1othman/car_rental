<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class ColorController extends GenericController
{
    public function __construct()
    {
        parent::__construct('color');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields = ['color_code','is_active'];
    }

    public function store(Request $request)
    {
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'color_code' => 'required|string|max:255',
            'description.*' => 'nullable|string',
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
        // Define validation rules
        $this->validationRules = [
            'color_code' => 'required|string|max:255',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
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
