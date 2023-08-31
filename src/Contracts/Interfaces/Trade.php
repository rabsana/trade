<?php

namespace Rabsana\Trade\Contracts\Interfaces;

interface Trade
{
    public function match($number = 100, $pair = NULL);
}
