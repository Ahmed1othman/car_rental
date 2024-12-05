<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{

    public function toArray($request)
    {
        $currency = \App\Models\Currency::find(app('currency_id'));
        $locale = app()->getLocale()??"en";

        $carModel = $this->carModel ? $this->carModel->translations->where('locale', $locale)->first(): null;

//        'daily_main_price' => ceil($this->daily_main_price * $currency->exchange_rate),
//            'daily_discount_price' => ceil($this->daily_discount_price * $currency->exchange_rate),
//            'weekly_main_price' => ceil($this->weekly_main_price * $currency->exchange_rate),
//            'weekly_discount_price' => ceil($this->weekly_discount_price * $currency->exchange_rate),
//            'monthly_main_price' => ceil($this->monthly_main_price * $currency->exchange_rate),
//            'monthly_discount_price' =>ceil( $this->monthly_discount_price * $currency->exchange_rate),

        $daily_main_price =  ceil($this->daily_main_price * $currency->exchange_rate);
        $daily_discount_price =  ceil($this->daily_discount_price * $currency->exchange_rate);
        $weekly_main_price =  ceil($this->weekly_main_price * $currency->exchange_rate);
        $weekly_discount_price =  ceil($this->weekly_discount_price * $currency->exchange_rate);
        $monthly_main_price =  ceil($this->monthly_main_price * $currency->exchange_rate);
        $monthly_discount_price =  ceil($this->monthly_discount_price * $currency->exchange_rate);
        return [
            'id' => $this->id,
            'daily_main_price' => $daily_main_price,
            'daily_discount_price' => $daily_discount_price,
            'weekly_main_price' => $weekly_main_price,
            'weekly_discount_price' => $weekly_discount_price,
            'monthly_main_price' => $monthly_main_price,
            'monthly_discount_price' => $monthly_discount_price,
            'currency'=>[
                'name' => $currency->translations->first()->name,
                'code' => $currency->code,
                'symbol' => $currency->symbol,
            ],
            'crypto_payment_accepted' => $this->crypto_payment_accepted,
            'daily_mileage_included'=> $this->daily_mileage_included,
            'monthly_mileage_included'=> $this->monthly_mileage_included,
            'weakly_mileage_included'=> $this->weakly_mileage_included,
            'year' => $this->year->year??null,
            'door_count' => $this->door_count,
            'luggage_capacity' => $this->luggage_capacity,
            'passenger_capacity' => $this->passenger_capacity,
            'insurance_included' => $this->insurance_included,
            'free_delivery' => $this->free_delivery,
            'is_featured' => $this->is_featured,
            'is_flash_sale' => $this->is_flash_sale,
            'status' => $this->status,
            'gear_type' => $this->gearType->translations->where('locale', $locale)->first()->name,
            'color' => [
                'name' => $this->color->translations->where('locale', $locale)->first()->name ?? null,
                'code' => $this->color->color_code,
            ],
            'brand' => $this->brand->translations->where('locale', $locale)->first()->name ?? null,
            'car_model' => $carModel ? $carModel->name : null,
            'category' => $this->category->translations->where('locale', $locale)->first()->name ?? null,
            'default_image_path' => $this->default_image_path,
            'slug' => $this->translations->where('locale', $locale)->first()->slug ?? null,
            'name' => $this->translations->where('locale', $locale)->first()->name ?? null,
            'images' => collect([
                [
                    'file_path' => $this->default_image_path,
                    'alt' => 'Default Image',  // You can customize this
                    'type' => 'image',         // Default type
                ],
            ])->concat(
                $this->images->map(fn($image) => [
                    'file_path' => $image->file_path,
                    'alt' => $image->alt,
                    'type' => $image->type,
                ])
            ),
            'no_deposit' => $this->no_debosite??1,
            'discount_rate' => ceil(($this->daily_main_price - $this->daily_discount_price) * 100 / $this->daily_main_price),
        ];
    }
}
