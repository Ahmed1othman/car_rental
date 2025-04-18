<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Faq extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        
        // Default sorting by order
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }
}
