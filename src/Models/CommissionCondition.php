<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class CommissionCondition extends Model
{
    // Use modules, traits, plugins ...
    const OPERATORS = [
        'greaterThan'           => '> بزرگتر از',
        'greaterThanOrEqual'    => '>= بزرگتر مساوی از',
        'lessThan'              => '< کوچیکتر از',
        'lessThanOrEqual'       => '<= کوچیکتر مساوی از',
        'equal'                 => '== مساوی با',
        'notEqual'              => '!= نامساوی با'
    ];

    const PROPERTIES = [
        'orderVolume'           => 'حجم معاملات'
    ];


    // Config the model
    protected $guarded = ['id'];


    // Filters


    // Relations
    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    // Accessors
    public function getOperandAttribute($value)
    {
        return Math::number((float)$value);
    }

    // Mutators


    // Extra methods
}
