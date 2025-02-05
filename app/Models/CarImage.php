<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'car_id',
        'file_path',
        'thumbnail_path',
        'alt',
        'type'
    ];
}
