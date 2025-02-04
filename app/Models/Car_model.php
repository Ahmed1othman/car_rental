<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Car_model extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'car_models';

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(Car_modelTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }

    // إضافة العلاقة مع الماركة
    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    public function cars(): HasMany{
        return $this->hasMany(Car::class,'car_model_id','id');
    }

}
