<?php

namespace App\Models;

use App\Models\old\CarImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function CarModel():BelongsTo{
        return $this->belongsTo(Car_model::class,'car_model_id');
    }

    public function color():BelongsTo{
        return $this->belongsTo(Color::class);
    }

    public function gearType():BelongsTo{
        return $this->belongsTo(Gear_type::class,'gear_type_id');
    }



    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Blog::class, BlogCar::class, 'car_id', 'blog_id');
    }

}
