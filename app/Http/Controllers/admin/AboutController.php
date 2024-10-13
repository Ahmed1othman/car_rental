<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class AboutController extends GenericController
{
    public function __construct()
    {
        parent::__construct('about');
        $this->seo_question =true;
        $this->slugField ='page_name';
        $this->translatableFields = [
            'about_main_header_title',
            'about_main_header_paragraph',
            'why_choose_content',
            'our_vision_content',
            'our_mission_content'
        ];
        $this->nonTranslatableFields = ['page_name','is_active'];
        $this->uploadedfiles = [
            'our_mission_image_path',
            'our_vision_image_path',
            'why_choose_image_path',
        ];
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'page_name' => 'required|string|max:255',
            'about_main_header_title.*' => 'nullable|string',
            'about_main_header_paragraph.*' => 'nullable|string',
            'why_choose_content.*' => 'nullable|string',
            'our_mission_content.*' => 'nullable|string',
            'our_vision_content.*' => 'nullable|string',

            'why_choose_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'our_vision_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'our_mission_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',

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
            'page_name' => 'required|string|max:255',
            'about_main_header_title.*' => 'nullable|string',
            'about_main_header_paragraph.*' => 'nullable|string',
            'why_choose_content.*' => 'nullable|string',
            'our_mission_content.*' => 'nullable|string',
            'our_vision_content.*' => 'nullable|string',

            'why_choose_image_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'our_vision_image_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'our_mission_image_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',

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
        parent::update($request, $id);
        return back()->with('success', 'About Page updated successfully.');
    }

}
