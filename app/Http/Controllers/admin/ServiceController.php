<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class ServiceController extends GenericController
{
    public function __construct()
    {
        parent::__construct('service');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name','description'];
        $this->nonTranslatableFields = ['is_active','show_in_home'];
        $this->uploadedfiles = ['image_path'];
    }

    public function store(Request $request)
    {
        $request->merge([
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'image_path' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'description.*' => 'nullable|string',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'is_active' => 'boolean',
            'show_in_home' => 'boolean',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        // Define validation rules
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'image_path' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
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
