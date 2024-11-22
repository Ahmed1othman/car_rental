<?php

namespace App\Models;

use App\Models\old\CarImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Car extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(CarTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }

    public function images(): HasMany{
        return $this->hasMany(CarImage::class);
    }

    public function brand():BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    //car_models with cars
    public function carModel():BelongsTo{
        return $this->belongsTo(Car_model::class,'car_model_id','id');
    }

    public function color():BelongsTo{
        return $this->belongsTo(Color::class);
    }

    public function year():BelongsTo{
        return $this->belongsTo(Year::class);
    }

    public function gearType():BelongsTo{
        return $this->belongsTo(Gear_type::class,'gear_type_id');
    }



    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, BlogCar::class, 'car_id', 'blog_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, CarFeature::class, 'car_id', 'feature_id');
    }

}
