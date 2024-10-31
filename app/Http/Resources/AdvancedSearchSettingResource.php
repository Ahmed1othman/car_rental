<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvancedSearchSettingResource extends JsonResource
{

    public function toArray($request)
    {


        return [
            'brands'=> BrandResource::collection($this->brands),
            'categories'=> CategoryResource::collection($this->categories),
            'gear_types'=> GearTypeResource::collection($this->gear_types),
            'colors'=> GearTypeResource::collection($this->colors),
        ];
    }
}
