<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'name', 'exchange_rate', 'is_default','is_active','symbol'];

    protected $casts = [
        'exchange_rate' => 'float',
        'is_default' => 'boolean',
    ];

    public function setAsDefault()
    {
        self::where('is_default', true)->whereNot('id',$this->id)->update(['is_default' => false]);
        $this->update([
            'is_default' => 1,
            'is_active' => 1
        ]);
        $this->save();
    }

    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }

    public function calculateRate($amount, Currency $toCurrency)
    {
        $defaultCurrency = self::getDefault();

        if ($this->is_default) {
            return $amount * $toCurrency->exchange_rate;
        }

        $amountInDefault = $amount / $this->exchange_rate;
        return $amountInDefault * $toCurrency->exchange_rate;
    }
}
