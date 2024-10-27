<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailedCarResource extends JsonResource
{
    public function toArray($request)
    {
        $currency = \App\Models\Currency::find(app('currency_id'));
        $local = app()->getLocale()??"en";
        return [
            'id' => $this->id,
            'daily_main_price' => ceil($this->daily_main_price * $currency->exchange_rate),
            'daily_discount_price' => ceil($this->daily_discount_price * $currency->exchange_rate),
            'weekly_main_price' => ceil($this->weekly_main_price * $currency->exchange_rate),
            'weekly_discount_price' => ceil($this->weekly_discount_price * $currency->exchange_rate),
            'monthly_main_price' => ceil($this->monthly_main_price * $currency->exchange_rate),
            'monthly_discount_price' =>ceil( $this->monthly_discount_price * $currency->exchange_rate),
            'door_count' => $this->door_count,
            'luggage_capacity' => $this->luggage_capacity,
            'passenger_capacity' => $this->passenger_capacity,
            'insurance_included' => $this->insurance_included,
            'free_delivery' => $this->free_delivery,
            'is_featured' => $this->is_featured,
            'is_flash_sale' => $this->is_flash_sale,
            'status' => $this->status,
            'gear_type' => $this->gearType->translations->where('locale', $local)->first()->name,
            'color' => [
                'name' => $this->color->translations->where('locale', $local)->first()->name ?? null,
                'code' => $this->color->color_code,
            ],
            'brand' => $this->brand->translations->where('locale', $local)->first()->name ?? null,
            'category' => $this->category->translations->where('locale', $local)->first()->name ?? null,
            'default_image_path' => $this->default_image_path,
            'slug' => $this->translations->where('locale', $local)->first()->slug ?? null,
            'name' => $this->translations->where('locale', $local)->first()->name ?? null,
            'images' => $this->images->map(fn($image) => [
                'file_path' => $image->file_path,
                'alt' => $image->alt,
                'type' => $image->type,
            ]),

            'seo_data'=>[
                'meta_title' => $this->translations->first()->meta_title,
                'meta_description' => $this->translations->first()->meta_description,
                'meta_keywords' => $this->translations->first()->meta_keywords,
            ]
        ];
    }
}
