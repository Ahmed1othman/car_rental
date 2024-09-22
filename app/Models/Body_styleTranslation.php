<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Body_styleTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function bodyStyle(): BelongsTo
    {
        return $this->belongsTo(body_style::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
