<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class Commission extends Model
{
    // Use modules, traits, plugins ...


    // Config the model
    protected $guarded = ['id'];


    // Filters

    public function scopeName($query, $name = NULL)
    {
        if (!empty($name)) {
            return $query->where('name', 'LIKE', "%$name%");
        }
        return $query;
    }

    public function scopeSymbolQuote($query, $quote = NULL)
    {
        if (!empty($quote)) {
            return $query->whereRaw('UPPER(symbol_quote) = ?', [strtoupper($quote)]);
        }

        return $query;
    }

    public function scopeSearch($query, $search = NULL)
    {
        if (!empty($search)) {
            return $query->where(function ($q) use ($search) {
                $q->orWhere('name', 'LIKE', "%$search%")
                    ->orWhere('symbol_quote', 'LIKE', "%$search%");
            });
        }

        return $query;
    }

    // Relations

    public function conditions()
    {
        return $this->commission_conditions();
    }


    // Accessors

    public function getTakerFeeAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMakerFeeAttribute($value)
    {
        return Math::number((float)$value);
    }

    // Mutators
    public function setSymbolQuoteAttribute($symbolQuote)
    {
        $this->attributes['symbol_quote'] = strtoupper($symbolQuote);
    }

    // Extra methods
    public function commission_conditions()
    {
        return $this->hasMany(CommissionCondition::class);
    }
}
