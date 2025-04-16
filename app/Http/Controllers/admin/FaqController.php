<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController extends GenericController
{
    public function __construct()
    {
        parent::__construct('faq');
        $this->seo_question = true;
        $this->slugField = 'question';
        $this->translatableFields = ['question', 'answer'];
        $this->nonTranslatableFields = ['is_active', 'show_in_home', 'order'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        $this->validationRules = [
            'order' => 'required|integer|min:0',
            'question.*' => 'required|string',
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
        // Log incoming request data
        Log::info('FAQ Update - Request Data:', $request->all());
        
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);

        // Define validation rules
        $this->validationRules = [
            'order' => 'required|integer|min:0',
            'question.*' => 'required|string',
            'answer.*' => 'nullable|string',
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
            'order.required' => 'The order field is required.',
            'order.integer' => 'The order must be a number.',
            'order.min' => 'The order must be at least 0.',
            'question.*.required' => 'The question field is required.',
        ];

        // Log validation rules
        Log::info('FAQ Update - Validation Rules:', $this->validationRules);

        // Validate the request
        $validatedData = $request->validate($this->validationRules, $this->validationMessages);
        Log::info('FAQ Update - Validated Data:', $validatedData);

        try {
            $result = parent::update($request, $id);
            Log::info('FAQ Update - Success');
            return $result;
        } catch (\Exception $e) {
            Log::error('FAQ Update - Error:', ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
