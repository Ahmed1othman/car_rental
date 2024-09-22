<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class FeatureController extends GenericController
{
    public function __construct()
    {
        parent::__construct('feature');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields = ['is_active'];
    }

    public function store(Request $request)
    {
        $request->all();
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
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
            'name.*' => 'required|string|max:255',
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
