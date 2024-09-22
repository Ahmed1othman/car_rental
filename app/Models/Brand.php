<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Brand extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(BrandTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }

    public function carModels(): HasMany{
        return $this->hasMany(CarModel::class);
    }

}
