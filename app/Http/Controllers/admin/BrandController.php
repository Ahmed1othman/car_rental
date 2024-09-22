<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class BrandController extends GenericController
{
    public function __construct()
    {
        parent::__construct('brand');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields = ['is_active'];
        $this->uploadedfiles = ['logo_path'];
    }

    public function store(Request $request)
    {
        $this->validationRules = [
            'logo_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'name.*' => [
        'required',
        function ($attribute, $value, $fail) {
            // Similar logic as explained before
            preg_match('/name\.(\w+)/', $attribute, $matches);
            $locale = $matches[1] ?? null;

            if ($locale) {
                $exists = \App\Models\BrandTranslation::where('name', $value)
                    ->where('locale', $locale)
                    ->exists();

                if ($exists) {
                    $fail("The name '{$value}' has already been taken for the locale '{$locale}'.");
                }
            }
        }],
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
            'logo_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'name.*' => [
            'required','string','max:255',
            function ($attribute, $value, $fail) use ($id) {
                // Extract the locale from the field name (e.g., name.en, name.fr)
                preg_match('/name\.(\w+)/', $attribute, $matches);
                $locale = $matches[1] ?? null;

                if ($locale) {
                    // Get the brand ID being updated from the request or route

                    // Check if a record with the same name and locale already exists
                    $exists = \App\Models\BrandTranslation::where('name', $value)
                        ->where('locale', $locale)
                        ->where('brand_id', '!=', $id) // Ignore the current brand's translation
                        ->exists();

                    if ($exists) {
                        $fail("The name '{$value}' has already been taken for the locale '{$locale}'.");
                    }
                }
},],
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
        return parent::update($request, $id);
    }

}
