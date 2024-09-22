<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarMaker extends Model
{
    use HasFactory;
    protected $guarded = [];


    //rel
    public function cars():HasMany
    {
        return $this->hasMany(Car::class, 'car_maker_id');
    }




}
