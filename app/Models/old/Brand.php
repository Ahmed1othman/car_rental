<?php

namespace App\Models\old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['meta_keywords_en_list','meta_keywords_ar_list'];

    public function getMetaKeywordsEnListAttribute()
    {
        $keywords = json_decode($this->attributes['meta_keywords_en'], true);
        return $keywords ? implode(', ', array_column($keywords, 'value')) : '';
    }

    public function getMetaKeywordsArListAttribute()
    {
        $keywords = json_decode($this->attributes['meta_keywords_ar'], true);
        return $keywords ? implode(', ', array_column($keywords, 'value')) : '';
    }

}
