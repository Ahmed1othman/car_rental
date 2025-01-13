<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class DocumentController extends GenericController
{
    public function __construct()
    {
        parent::__construct('document');
        $this->seo_question =true;
        $this->slugField ='content';
        $this->translatableFields = ['content'];
        $this->nonTranslatableFields = ['is_active','for'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'for' => 'required|in:native,foreign',
            'content.*' => 'required|string|max:255',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $this->validationRules = [
            'for' => 'required|in:native,foreign',
            'content.*' => 'required|string|max:255',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
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
