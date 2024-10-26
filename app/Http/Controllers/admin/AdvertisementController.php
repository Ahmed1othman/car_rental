<?php
namespace App\Http\Controllers\admin;
use App\Models\AdvertisementPosition;
use Illuminate\Http\Request;

class AdvertisementController extends GenericController
{
    public function __construct()
    {
        parent::__construct('advertisement');
        $this->seo_question =true;
        $this->slugField ='title';
        $this->translatableFields = ['title','description'];
        $this->nonTranslatableFields = ['advertisement_position_id'];
        $this->uploadedfiles = ['mobile_image_path','web_image_path'];
    }

    public function create()
    {
        $this->data['advertisementPositions'] = AdvertisementPosition::leftJoin('advertisements', 'advertisement_positions.id', '=', 'advertisements.advertisement_position_id')
            ->whereNull('advertisements.advertisement_position_id') // Exclude positions already used
            ->select('advertisement_positions.*') // Select all columns from advertisement_positions
            ->get();

        return parent::create();
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'title.*' => 'nullable|string|max:255',
            'description.*' => 'nullable|string',
            'advertisement_position_id' => 'required|exists:advertisement_positions,id',
            'mobile_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'web_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
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
            'title.*' => 'nullable|string|max:255',
            'description.*' => 'nullable|string',
            'advertisement_position_id' => 'required|exists:advertisement_positions,id',
            'mobile_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'web_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
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
