<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodCar extends Model
{
    use HasFactory;

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
    
}
