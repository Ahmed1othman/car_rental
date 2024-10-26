<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Advertisement extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(AdvertisementTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }

    public function advertisementPosition(): BelongsTo
    {
        return $this->belongsTo(AdvertisementPosition::class);
    }

}
