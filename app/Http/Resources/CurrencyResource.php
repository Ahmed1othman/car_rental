<?php

namespace App\Http\Resources;

use App\Models\StaticTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale()??"ar";
        $translations = $this->translations->where('locale',$locale)->first();
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'code' => $this->code,
            'exchange_rate' => $this->exchange_rate,
            'name' => $translations->name,
            'is_default' => $this->is_default,
        ];
    }

}
