<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class CurrencyController extends GenericController
{
    public function __construct()
    {
        parent::__construct('currency');
        $this->seo_question =false;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields= ['code','symbol','is_active','is_default'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'is_default' => $request->has('is_default') ? true : false,
        ]);
        $this->validationRules = [
            'code' => 'required|string|max:255',
            'symbol' => 'required|string|max:255',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'is_default' => $request->has('is_default') ? true : false,
        ]);
        // Define validation rules
        $this->validationRules = [
            'code' => 'required|string|max:255',
            'symbol' => 'required|string|max:255',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

}
