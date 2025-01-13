<?php
namespace App\Http\Controllers\admin;
use App\Models\Icon;
use Illuminate\Http\Request;

class FeatureController extends GenericController
{
    public function __construct()
    {
        parent::__construct('feature');
        $this->seo_question =true;
        $this->slugField ='name';
        $this->translatableFields = ['name'];
        $this->nonTranslatableFields = ['is_active','icon_id'];
    }

    public function create()
    {
        $this->data['icons'] = Icon::get();
        return parent::create();
    }

    public function edit($id)
    {
        $this->data['icons'] = Icon::all();
        return parent::edit($id);
    }
    public function store(Request $request)
    {
        $request->all();
        $this->validationRules = [
            'name.*' => 'required|string|max:255',
            'icon_id' => 'required|exists:icons,id',
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
            'name.*' => 'required|string|max:255',
            'icon_id' => 'required|exists:icons,id',
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
