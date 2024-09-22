<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function car(): BelongsTo
    {
        return $this->belongsTo(car::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
