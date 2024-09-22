<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarIncludedFeature extends Model
{
    use HasFactory;
    protected $guarded = [];


    //rel
    public function car(): BelongsToMany
    {
        return $this->belongsToMany(IncludedFeature::class,'car_include_features','car_id','included_feature_id');
    }
}
