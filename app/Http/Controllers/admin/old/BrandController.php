<?php
namespace App\Http\Controllers\admin\old;
use App\Http\Controllers\admin\GenericController;
use Illuminate\Http\Request;

class BrandController extends GenericController
{
    public function __construct()
    {
        parent::__construct('Brand');
        $this->seo_question =true;
        $this->slugFieldAr ='name_ar';
        $this->slugFieldEn ='name_en';
        $this->files=['logo_path'];
    }

    public function store(Request $request)
    {
        $this->validationRules = [
            'name_en' => 'required|unique:brands,name_en',
            'name_ar' => 'required|unique:brands,name_ar',
            'meta_title_en' => 'nullable',
            'meta_title_ar' => 'nullable',
            'meta_description_en' => 'nullable',
            'meta_description_ar' => 'nullable',
            'meta_keywords_en' => 'nullable',
            'meta_keywords_ar' => 'nullable',
            'logo_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        $this->validationMessages = [
            'name_en.required' => 'يرجى إدخال اسم العلامة التجارية باللغة الإنجليزية.',
            'name_ar.required' => 'يرجى إدخال اسم العلامة التجارية باللغة العربية.',
            'name_en.unique' => 'الاسم التجاري باللغة الإنجليزية تم أخذه بالفعل.',
            'name_ar.unique' => 'الاسم التجاري باللغة العربية تم أخذه بالفعل.',
            'meta_title_en.required' => 'يرجى إدخال العنوان التعريفي باللغة الإنجليزية.',
            'meta_title_ar.required' => 'يرجى إدخال العنوان التعريفي باللغة العربية.',
            'meta_description_en.required' => 'يرجى إدخال الوصف التعريفي باللغة الإنجليزية.',
            'meta_description_ar.required' => 'يرجى إدخال الوصف التعريفي باللغة العربية.',
            'meta_keywords_en.required' => 'يرجى إدخال الكلمات المفتاحية باللغة الإنجليزية.',
            'meta_keywords_ar.required' => 'يرجى إدخال الكلمات المفتاحية باللغة العربية.',
            'logo_path.required' => 'يرجى رفع شعار العلامة التجارية.',
            'logo_path.mimes' => 'يجب أن يكون الشعار ملفًا من نوع: jpg, jpeg, png,webp.',
            'logo_path.max' => 'يجب ألا يتجاوز حجم الشعار 4 ميجابايت.',
        ];
        return parent::store($request);

    }

    public function update(Request $request,$id)
    {

        $this->validationRules = [
            'name_en' => 'required',
            'name_ar' => 'required',
            'meta_title_en' => 'nullable',
            'meta_title_ar' => 'nullable',
            'meta_description_en' => 'nullable',
            'meta_description_ar' => 'nullable',
            'meta_keywords_en' => 'nullable',
            'meta_keywords_ar' => 'nullable',
            'logo_path' => 'sometimes|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        $this->validationMessages = [
            'name_en.required' => 'يرجى إدخال اسم العلامة التجارية باللغة الإنجليزية.',
            'name_ar.required' => 'يرجى إدخال اسم العلامة التجارية باللغة العربية.',
            'meta_title_en.required' => 'يرجى إدخال العنوان التعريفي باللغة الإنجليزية.',
            'meta_title_ar.required' => 'يرجى إدخال العنوان التعريفي باللغة العربية.',
            'meta_description_en.required' => 'يرجى إدخال الوصف التعريفي باللغة الإنجليزية.',
            'meta_description_ar.required' => 'يرجى إدخال الوصف التعريفي باللغة العربية.',
            'meta_keywords_en.required' => 'يرجى إدخال الكلمات المفتاحية باللغة الإنجليزية.',
            'meta_keywords_ar.required' => 'يرجى إدخال الكلمات المفتاحية باللغة العربية.',
            'logo_path.mimes' => 'يجب أن يكون الشعار ملفًا من نوع: jpg, jpeg, png.',
            'logo_path.max' => 'يجب ألا يتجاوز حجم الشعار 4 ميجابايت.',
        ];

        return parent::update($request, $id);
    }

}
