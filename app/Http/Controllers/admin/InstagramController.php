<?php
namespace App\Http\Controllers\admin;
use App\Models\Instagram;
use Illuminate\Http\Request;

class InstagramController extends GenericController
{
    public function __construct()
    {
        parent::__construct('instagram');
        $this->seo_question =false;
        $this->nonTranslatableFields = ['show_in_home', 'is_active'];
        $this->uploadedfiles = ['instagram_url'];
        $this->isTranslatable = false;
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
            'show_in_home' => $request->has('show_in_home') ? true : false,
        ]);
        $this->validationRules = [
            'instagram_url' => 'required|string|max:255',
        ];

        $request->validate($this->validationRules);
        Instagram::create($request->only('instagram_url', 'is_active','show_in_home'));
        return redirect()->route('admin.instagrams.index')
            ->with('success', 'data added successfully');

    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $this->validationRules = [
            'instagram_url' => 'required|string|max:255',
        ];
        $request->validate($this->validationRules);
        // Delegate to the generic controller's update function
        Instagram::where('id', $id)->update($request->only('instagram_url', 'is_active','show_in_home'));
        return redirect()->route('admin.instagrams.index')
            ->with('success', 'row updated successfully');
    }

}
