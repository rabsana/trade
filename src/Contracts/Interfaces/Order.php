<?php

namespace Rabsana\Trade\Contracts\Interfaces;
use Rabsana\Trade\Models\Symbol;

interface Order
{
    public function store(Symbol $symbol);
}
