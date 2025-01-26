<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class FaqController extends GenericController
{
    public function __construct()
    {
        parent::__construct('faq');
        $this->seo_question =true;
        $this->slugField ='question';
        $this->translatableFields = ['question','answer'];
        $this->nonTranslatableFields = ['is_active','show_in_home', 'order'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        $this->validationRules = [
            'order' => 'required|integer|min:0',
            'question.*' => 'required|string|max:255',
            'answer.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
            'show_in_home' => 'boolean',
        ];

        $this->validationMessages = [
            'order.required' => 'The order field is required.',
            'order.integer' => 'The order must be a number.',
            'order.min' => 'The order must be at least 0.',
        ];
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        // Define validation rules
        $this->validationRules = [
            'order' => 'required|numeric',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
            'show_in_home' => 'boolean',
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }
}
