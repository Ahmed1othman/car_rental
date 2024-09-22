<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function feature(): BelongsTo
    {
        return $this->belongsTo(feature::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
