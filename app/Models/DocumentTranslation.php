<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];


    //relations
    public function template(): BelongsTo
    {
        return $this->belongsTo(document::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

}