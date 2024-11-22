<?php

namespace App\Http\Resources;

use App\Models\StaticTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale()?? "en";
        $translations = $this->translations->where('locale',$locale)->first();
        $car_counts = $this->getCounts($locale);

        return [
            'id' => $this->id,
            'slug' => $translations->slug,
            'name' => $translations->name,
            'description' => $translations->description,
            'image' => $this->image_path,
            'car_count'=>$car_counts,
        ];
    }

    /**
     * @param string $language
     * @return string
     */
    public function getCounts(string $language): string
    {
        $car = StaticTranslation::where('locale', $language)->where('key', 'car')->first();
        $cars = StaticTranslation::where('locale', $language)->where('key', 'cars')->first();
        $count = $this->cars->count();
        if ($language == 'ar'){
            if ($count < 2 && $count >10)
                $car_counts = $count . " " . $cars->value;
            else if ($count == 2)
                $car_counts = "سيارتان";
            else
                $car_counts = $count. " ". $car->value;
        } else{
            if ($count <= 1)
                $car_counts = $count . " " . $car->value;
            else
                $car_counts = $count . " " . $cars->value;
        }

        return $car_counts;
    }
}
