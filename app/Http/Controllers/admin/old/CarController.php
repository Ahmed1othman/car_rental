<?php
namespace App\Http\Controllers\admin\old;
use App\Http\Controllers\admin\GenericController;
use App\Models\old\BodyStyle;
use App\Models\old\Brand;
use App\Models\old\CarMaker;
use App\Models\old\IncludedFeature;
use Illuminate\Http\Request;

class CarController extends GenericController
{
    public function __construct()
    {
        parent::__construct('car');
        $this->slugFieldAr ='name_ar';
        $this->slugFieldEn ='name_en';
    }

    public function create(){
        $this->data['brands'] = Brand::select('id','name_'.app()->getLocale())->get();
        $this->data['categories'] = Brand::select('id','name_'.app()->getLocale())->get();
        $this->data['carMakers'] = CarMaker::select('id','name_'.app()->getLocale())->get();
        $this->data['bodyStyles'] = BodyStyle::select('id','name_'.app()->getLocale())->get();
        $this->data['includedFeatures'] = IncludedFeature::select('id','name_'.app()->getLocale())->get();
        return parent::create();
    }

    public function store(Request $request)
    {

        $this->validationRules = [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'car_type' => 'required|string|max:100',
            'daily_main_price' => 'required|numeric|min:0',
            'daily_discount_price' => 'nullable|numeric|min:0|lt:daily_main_price',
            'monthly_main_price' => 'required|numeric|min:0',
            'monthly_discount_price' => 'nullable|numeric|min:0|lt:monthly_main_price',
            'door_count' => 'required|integer|min:1|max:10',
            'luggage_capacity' => 'required|integer|min:0|max:10',
            'gear_type' => 'required|in:manual,automatic',
            'passenger_capacity' => 'required|integer|min:1|max:50',
            'insurance_included' => 'boolean',
            'free_delivery' => 'boolean',
            'is_featured' => 'boolean',
            'is_flash_sale' => 'boolean',
            'car_maker' => 'required|string|max:255',
            'body_style' => 'required|string|max:255',
            'includes' => 'array',
            'includes.*' => 'exists:includes,id',
        ];

        $this->validationMessages = [
        'name_ar.required' => 'يرجى إدخال اسم السيارة باللغة العربية.',
        'name_ar.string' => 'يجب أن يكون اسم السيارة باللغة العربية نصاً.',
        'name_ar.max' => 'يجب ألا يزيد اسم السيارة باللغة العربية عن 255 حرفاً.',

        'name_en.required' => 'يرجى إدخال اسم السيارة باللغة الإنجليزية.',
        'name_en.string' => 'يجب أن يكون اسم السيارة باللغة الإنجليزية نصاً.',
        'name_en.max' => 'يجب ألا يزيد اسم السيارة باللغة الإنجليزية عن 255 حرفاً.',

        'brand_id.required' => 'يرجى اختيار العلامة التجارية.',
        'brand_id.exists' => 'العلامة التجارية المختارة غير موجودة.',

        'category_id.required' => 'يرجى اختيار الفئة.',
        'category_id.exists' => 'الفئة المختارة غير موجودة.',

        'car_type.required' => 'يرجى إدخال نوع السيارة.',
        'car_type.string' => 'يجب أن يكون نوع السيارة نصاً.',
        'car_type.max' => 'يجب ألا يزيد نوع السيارة عن 100 حرف.',

        'daily_main_price.required' => 'يرجى إدخال السعر اليومي الأساسي.',
        'daily_main_price.numeric' => 'يجب أن يكون السعر اليومي الأساسي رقماً.',
        'daily_main_price.min' => 'يجب أن يكون السعر اليومي الأساسي أكبر من أو يساوي 0.',

        'daily_discount_price.numeric' => 'يجب أن يكون السعر اليومي بعد الخصم رقماً.',
        'daily_discount_price.min' => 'يجب أن يكون السعر اليومي بعد الخصم أكبر من أو يساوي 0.',
        'daily_discount_price.lt' => 'يجب أن يكون السعر اليومي بعد الخصم أقل من السعر اليومي الأساسي.',

        'monthly_main_price.required' => 'يرجى إدخال السعر الشهري الأساسي.',
        'monthly_main_price.numeric' => 'يجب أن يكون السعر الشهري الأساسي رقماً.',
        'monthly_main_price.min' => 'يجب أن يكون السعر الشهري الأساسي أكبر من أو يساوي 0.',

        'monthly_discount_price.numeric' => 'يجب أن يكون السعر الشهري بعد الخصم رقماً.',
        'monthly_discount_price.min' => 'يجب أن يكون السعر الشهري بعد الخصم أكبر من أو يساوي 0.',
        'monthly_discount_price.lt' => 'يجب أن يكون السعر الشهري بعد الخصم أقل من السعر الشهري الأساسي.',

        'door_count.required' => 'يرجى إدخال عدد الأبواب.',
        'door_count.integer' => 'يجب أن يكون عدد الأبواب عدداً صحيحاً.',
        'door_count.min' => 'يجب أن يكون عدد الأبواب على الأقل 1.',
        'door_count.max' => 'يجب ألا يزيد عدد الأبواب عن 10.',

        'luggage_capacity.required' => 'يرجى إدخال سعة الحقائب.',
        'luggage_capacity.integer' => 'يجب أن تكون سعة الحقائب عدداً صحيحاً.',
        'luggage_capacity.min' => 'يجب أن تكون سعة الحقائب على الأقل 0.',
        'luggage_capacity.max' => 'يجب ألا تزيد سعة الحقائب عن 10.',

        'gear_type.required' => 'يرجى اختيار نوع ناقل الحركة.',
        'gear_type.in' => 'نوع ناقل الحركة يجب أن يكون "يدوي" أو "أوتوماتيكي".',

        'passenger_capacity.required' => 'يرجى إدخال عدد الركاب.',
        'passenger_capacity.integer' => 'يجب أن يكون عدد الركاب عدداً صحيحاً.',
        'passenger_capacity.min' => 'يجب أن يكون عدد الركاب على الأقل 1.',
        'passenger_capacity.max' => 'يجب ألا يزيد عدد الركاب عن 50.',

        'insurance_included.boolean' => 'يجب أن تكون قيمة "تضمين التأمين" صحيحة أو خاطئة.',
        'free_delivery.boolean' => 'يجب أن تكون قيمة "التوصيل المجاني" صحيحة أو خاطئة.',
        'is_featured.boolean' => 'يجب أن تكون قيمة "مميز" صحيحة أو خاطئة.',
        'is_flash_sale.boolean' => 'يجب أن تكون قيمة "تخفيض مؤقت" صحيحة أو خاطئة.',

        'car_maker.required' => 'يرجى إدخال صانع السيارة.',
        'car_maker.string' => 'يجب أن يكون صانع السيارة نصاً.',
        'car_maker.max' => 'يجب ألا يزيد اسم صانع السيارة عن 255 حرفاً.',

        'body_style.required' => 'يرجى إدخال نمط الهيكل.',
        'body_style.string' => 'يجب أن يكون نمط الهيكل نصاً.',
        'body_style.max' => 'يجب ألا يزيد نمط الهيكل عن 255 حرفاً.',

        'includes.array' => 'يجب أن يكون الحقل "يشمل" مصفوفة.',
        'includes.*.exists' => 'القيمة المختارة لـ "يشمل" غير موجودة.',
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
            'logo_path' => 'sometimes|mimes:jpg,jpeg,png|max:4096',
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
