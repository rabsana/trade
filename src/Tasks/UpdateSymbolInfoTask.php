<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UpdateSymbolInfoTask
{
    public function run()
    {
        try {
            Artisan::call('symbol:info');
        } catch (Exception $e) {
            Log::debug("Update-symbol-info-error: " . $e);
        }
    }
}
