<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bodyStyle() :BelongsTo
    {
        return $this->belongsTo(BodyStyle::class, 'body_style_id');
    }

    public function carMaker() :BelongsTo
    {
        return $this->belongsTo(CarMaker::class, 'car_maker_id');
    }

    public function includeFeatures(): BelongsToMany
    {
        return $this->belongsToMany(CarIncludedFeature::class, 'car_id', 'include_id');
    }

}
