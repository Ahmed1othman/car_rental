<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car_modelTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'car_model_translations';

    //relations
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(Car_model::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
