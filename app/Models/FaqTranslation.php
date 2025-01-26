<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }
}
