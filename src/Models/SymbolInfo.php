<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class SymbolInfo extends Model
{
    // Use modules, traits, plugins ...


    // Config the model

    protected $table = 'symbol_info';

    protected $guarded = ['id'];


    // Filters


    // Relations

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }



    // Accessors

    public function getPriceAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getPricePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->price);
    }

    public function getLastDayHighAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getLastDayHighPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->last_day_high);
    }

    public function getLastDayLowAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getLastDayLowPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->last_day_low);
    }

    public function getLastDayBaseVolumeAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getLastDayBaseVolumePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->last_day_base_volume);
    }

    public function getTodayHighAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getTodayHighPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->today_high);
    }

    public function getTodayLowAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getTodayLowPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->today_low);
    }

    public function getTodayBaseVolumeAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getTodayBaseVolumePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->today_base_volume);
    }

    public function getChangePercentAttribute($value): string
    {
        return Math::number((float)$value);
    }


    // Mutators


    public function setPriceAttribute($price): void
    {
        $this->attributes['price'] = Math::number((float)$price);
    }

    public function setLastDayHighAttribute($last_day_high): void
    {
        $this->attributes['last_day_high'] = Math::number((float)$last_day_high);
    }

    public function setLastDayLowAttribute($last_day_low): void
    {
        $this->attributes['last_day_low'] = Math::number((float)$last_day_low);
    }

    public function setLastDayBaseVolumeAttribute($last_day_base_volume): void
    {
        $this->attributes['last_day_base_volume'] = Math::number((float)$last_day_base_volume);
    }

    public function setTodayHighAttribute($today_high): void
    {
        $this->attributes['today_high'] = Math::number((float)$today_high);
    }

    public function setTodayLowAttribute($today_low): void
    {
        $this->attributes['today_low'] = Math::number((float)$today_low);
    }

    public function setTodayBaseVolumeAttribute($today_base_volume): void
    {
        $this->attributes['today_base_volume'] = Math::number((float)$today_base_volume);
    }

    public function setChangePercentAttribute($change_percent): void
    {
        $this->attributes['change_percent'] = Math::number((float)$change_percent);
    }

    // Extra methods

}
