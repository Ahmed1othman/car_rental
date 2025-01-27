<?php
namespace App\Http\Controllers\admin;
use App\Models\Brand;
use Illuminate\Http\Request;

class Car_modelController extends GenericController
{
    public function __construct()
    {
        parent::__construct('car_model');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields = ['is_active','brand_id'];
    }
    public function create()
    {
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        return parent::create(); // TODO: Change the autogenerated stub
    }

    public function edit($id)
    {
        $locale = $this->data['defaultLocale'];
        $this->data['brands'] = Brand::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);}])->get();
        return parent::edit($id); // TODO: Change the autogenerated stub
    }

    public function store(Request $request)
    {
        $this->validationRules = [
            'brand_id' => 'required|exists:brands,id',
            'name.*' => 'required|string|max:255',
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
            'brand_id' => 'required|exists:brands,id',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
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
