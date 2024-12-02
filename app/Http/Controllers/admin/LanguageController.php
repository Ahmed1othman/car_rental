<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class LanguageController extends GenericController
{
    public function __construct()
    {
        parent::__construct('language');
        $this->nonTranslatableFields = ['code','name','flag','is_active'];
        $this->slugField ='name';
    }

    public function index()
    {
        $this->data['items'] = $this->model->paginate(10);
        return view('pages.admin.languages.index', $this->data);
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'code' => 'required|string|max:3|unique:languages,code',
            'name' => 'required|string|max:255|unique:languages,name',
            'flag' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {

        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        // Define validation rules
        $this->validationRules = [
            'code' => 'required|string|max:3|unique:languages,code,'.$id,
            'name' => 'required|string|max:255|unique:languages,name,'.$id,
            'flag' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        // Custom validation messages
        $this->validationMessages = [
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

}
