<?php
namespace App\Http\Controllers\admin;
use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends GenericController
{
    public function __construct()
    {
        parent::__construct('home');
        $this->seo_question =true;
        $this->slugField ='page_name';
        $this->translatableFields = [
            'hero_header_title',
            'car_only_section_title',
            'car_only_section_paragraph',
            'special_offers_section_title',
            'special_offers_section_paragraph',
            'why_choose_us_section_title',
            'why_choose_us_section_paragraph',
            'faq_section_title',
            'faq_section_paragraph',
            'where_find_us_section_title',
            'where_find_us_section_paragraph',
            'required_documents_section_title',
            'required_documents_section_paragraph',

        ];
        $this->nonTranslatableFields = ['page_name','is_active'];
        $this->uploadedfiles = [
            'hero_header_video_path',
        ];
    }

    public function edit($id)
    {
        $locale = $this->data['defaultLocale'];
        $this->data['cars'] = Car::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        return parent::edit($id);
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'page_name' => 'required|string|max:255',
            'car_only_section_title.*' => 'nullable|string',
            'car_only_section_paragraph.*' => 'nullable|string',
            'special_offers_section_title.*' => 'nullable|string',
            'special_offers_section_paragraph.*' => 'nullable|string',
            'why_choose_us_section_title.*' => 'nullable|string',
            'why_choose_us_section_paragraph.*' => 'nullable|string',
            'faq_section_paragraph.*' => 'nullable|string',
            'where_find_us_section_title.*' => 'nullable|string',
            'where_find_us_section_paragraph.*' => 'nullable|string',
            'faq_section_title.*' => 'nullable|string',
            'required_documents_section_title.*' => 'nullable|string',
            'required_documents_section_paragraph.*' => 'nullable|string',
            'hero_header_title.*' => 'nullable|string',
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
            'car_only_section_title.*' => 'nullable|string',
            'car_only_section_paragraph.*' => 'nullable|string',
            'special_offers_section_title.*' => 'nullable|string',
            'special_offers_section_paragraph.*' => 'nullable|string',
            'why_choose_us_section_title.*' => 'nullable|string',
            'why_choose_us_section_paragraph.*' => 'nullable|string',
            'faq_section_paragraph.*' => 'nullable|string',
            'where_find_us_section_title.*' => 'nullable|string',
            'where_find_us_section_paragraph.*' => 'nullable|string',
            'faq_section_title.*' => 'nullable|string',
            'required_documents_section_title.*' => 'nullable|string',
            'required_documents_section_paragraph.*' => 'nullable|string',
            'hero_header_title.*' => 'nullable|string',
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