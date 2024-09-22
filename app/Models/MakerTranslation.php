<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakerTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function maker(): BelongsTo
    {
        return $this->belongsTo(maker::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}
