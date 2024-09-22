<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IncludedFeature extends Model
{
    use HasFactory;
    protected $guarded = [];

    //rel
    public function cars() :BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_include_features', 'include_feature_id', 'car_id');
    }
}
