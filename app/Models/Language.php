<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Language extends Model
{
    use HasFactory;
    protected $guarded = [];

    //scope
    public function scopeActive($query){
        return $query->where('is_active', 1);
    }


}
