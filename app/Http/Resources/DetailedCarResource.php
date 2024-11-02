<?php
namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;

class DetailedCarResource extends JsonResource
{
    public function toArray($request)
    {
        // Cache currency exchange rate and locale
        $currencyExchangeRate = Currency::find(app('currency_id'))->exchange_rate ?? 1;
        $locale = app()->getLocale() ?? 'en';

        $carCategory = Car::where('category_id', $this->category_id)
            ->whereNot('id', $this->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Retrieve translation data for the current locale
        $translation = $this->translations->where('locale', $locale)->first();
        $gearTypeName = $this->gearType->translations->where('locale', $locale)->first()->name ?? null;
        $colorTranslation = $this->color->translations->where('locale', $locale)->first();
        $brandName = $this->brand->translations->where('locale', $locale)->first()->name ?? null;
        $carModel = $this->carModel ? $this->carModel->translations->where('locale', $locale)->first(): null;

        $categoryName = $this->category->translations->where('locale', $locale)->first()->name ?? null;


        // Calculate and round prices
        $prices = [
            'daily_main_price' => ceil($this->daily_main_price * $currencyExchangeRate),
            'daily_discount_price' => ceil($this->daily_discount_price * $currencyExchangeRate),
            'weekly_main_price' => ceil($this->weekly_main_price * $currencyExchangeRate),
            'weekly_discount_price' => ceil($this->weekly_discount_price * $currencyExchangeRate),
            'monthly_main_price' => ceil($this->monthly_main_price * $currencyExchangeRate),
            'monthly_discount_price' => ceil($this->monthly_discount_price * $currencyExchangeRate),
        ];

        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;

        return array_merge($prices, [
            'id' => $this->id,
            'door_count' => $this->door_count,
            'luggage_capacity' => $this->luggage_capacity,
            'passenger_capacity' => $this->passenger_capacity,
            'insurance_included' => $this->insurance_included,
            'free_delivery' => $this->free_delivery,
            'is_featured' => $this->is_featured,
            'is_flash_sale' => $this->is_flash_sale,
            'status' => $this->status,
            'gear_type' => $gearTypeName,
            'color' => [
                'name' => $colorTranslation->name ?? null,
                'code' => $this->color->color_code ?? null,
            ],
            'brand' => $brandName,
            'car_model' => $carModel ? $carModel->name : null,
            'category' => $categoryName,
            'default_image_path' => $this->default_image_path,
            'slug' => $translation->slug ?? null,
            'name' => $translation->name ?? null,
            "description"=> $translation->description?? null,
            "long_description"=> $translation->long_description?? null,
            'images' => $this->images->map(fn($image) => [
                'file_path' => $image->file_path,
                'alt' => $image->alt,
                'type' => $image->type,
            ]),
            "car_features"=> FeatureResource::collection($this->features),


            'related_cars'=> CarResource::collection($carCategory),
            'seo_data' => [
                'meta_title' => $translation->meta_title ?? null,
                'meta_description' => $translation->meta_description ?? null,
                'meta_keywords' => $metaKeywords,
            ],
        ]);
    }
}
