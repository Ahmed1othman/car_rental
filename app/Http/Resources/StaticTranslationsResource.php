<?php
namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;

class StaticTranslationsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'menu'=>[
                'home'=>$this->home,
                'brands'=>$this->brands,
                'categories'=> $this->categories,
                'about_us'=> $this->about_us,
                'contact_us'=> $this->contact_us,
                'blog'=> $this->blog,
                'search'=> $this->search,
                'no_results'=> $this->no_results,
                'cars'=> $this->cars,
                'car'=> $this->car,
            ],
            'card'=>[
                'per_day'=> $this->per_day,
                'per_month'=> $this->per_month,
                'per_weak'=> $this->per_week,
                'free_delivery'=> $this->free_delivery,
                'insurance_included'=> $this->insurance_included,
                'crypto_payment_accepted'=> $this->crypto_payment_accepted,
                'km_per_day'=> $this->km_per_day,
                'km_per_month'=> $this->km_per_month,
                'km_per_week'=> $this->km_per_week,
                'km'=> $this->km,
                'sale'=> $this->sale,
                'no_deposit'=> $this->no_deposit,
                'brand'=> $this->brand,
                'model'=> $this->model,
                'year'=> $this->year,
                'colo'=> $this->colo,
                'category'=> $this->category,
                'car_over_view'=> $this->car_over_view,
                'car_features'=> $this->car_features,
                'related_cars'=> $this->related_cars,
                'car_description'=> $this->car_description,
            ],
            'footer'=>[
                'brand_section'=> $this->brand_section,
                'quick_links'=> $this->quick_links,
                'support'=> $this->support,
                'available_payment_methods'=> $this->available_payment_methods,

            ],
            'general'=>[
                'view_all'=> $this->view_all,
                'cars'=> $this->cars,
                'car'=> $this->car,
                'no_results'=> $this->no_results,
            ],
        ];
    }
}
