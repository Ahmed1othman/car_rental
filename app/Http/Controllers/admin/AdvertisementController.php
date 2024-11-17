<?php
namespace App\Http\Controllers\admin;
use App\Models\Advertisement;
use App\Models\AdvertisementPosition;
use Illuminate\Http\Request;

class AdvertisementController extends GenericController
{
    public function __construct()
    {
        parent::__construct('advertisement');
        $this->seo_question =false;
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

    public function edit($id)
    {
        // Find the advertisement being edited
        $advertisement = Advertisement::findOrFail($id);

        // Get all available advertisement positions and include the position currently used by this advertisement
        $this->data['advertisementPositions'] = AdvertisementPosition::leftJoin('advertisements', 'advertisement_positions.id', '=', 'advertisements.advertisement_position_id')
            ->where(function ($query) use ($advertisement) {
                $query->whereNull('advertisements.advertisement_position_id') // Exclude positions already used
                ->orWhere('advertisement_positions.id', $advertisement->advertisement_position_id); // Include current position
            })
            ->select('advertisement_positions.*') // Select all columns from advertisement_positions
            ->get();

        // Pass the advertisement data to the view
        $this->data['advertisement'] = $advertisement;

        return parent::edit($id);
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $this->validationRules = [
            'advertisement_position_id' => 'required|exists:advertisement_positions,id',
            'mobile_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
            'web_image_path' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        $this->validationMessages = [

        ];
        return parent::store($request);

    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $this->validationRules = [
            'advertisement_position_id' => 'required|exists:advertisement_positions,id',
            'mobile_image_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
            'web_image_path' => 'nullable|mimes:jpg,jpeg,png,webp|max:4096',
        ];

        // Custom validation messages
        $this->validationMessages = [
            // Define any custom messages if necessary
        ];

        // Delegate to the generic controller's update function
        return parent::update($request, $id);
    }

}
