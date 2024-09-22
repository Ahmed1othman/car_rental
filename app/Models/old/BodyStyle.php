<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BodyStyle extends Model
{
    use HasFactory;
    protected $guarded = [];

    //rel
    public function cars():HasMany
    {
        return $this->hasMany(Car::class, 'body_style_id');
    }
}
