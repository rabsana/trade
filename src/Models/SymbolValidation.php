<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class SymbolValidation extends Model
{
    // Use traits


    // Config the model

    protected $table = 'symbol_validation';

    protected $guarded = ['id'];


    // Filters


    // Relations

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }

    // Accessors

    public function getMinQtyAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMaxQtyAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMinPriceAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMaxPriceAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMinNotionalAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getMaxNotionalAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getPercentOrderPriceUpAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getPercentOrderPriceDownAttribute($value)
    {
        return Math::number((float)$value);
    }


    // Mutators


    // Extra methods
}
