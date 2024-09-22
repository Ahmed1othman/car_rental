<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gear_typeTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function gearType(): BelongsTo
    {
        return $this->belongsTo(gear_type::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
