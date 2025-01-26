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
        $this->nonTranslatableFields = ['is_active','show_in_home'];
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
            'order.required' => 'The display order field is required.',
            'order.integer' => 'The display order must be a number.',
            'order.min' => 'The display order must be at least 0.',
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
            'order.required' => 'The display order field is required.',
            'order.integer' => 'The display order must be a number.',
            'order.min' => 'The display order must be at least 0.',
        ];
        return parent::update($request, $id);
    }

}
