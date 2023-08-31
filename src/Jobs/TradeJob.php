<?php

namespace Rabsana\Trade\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rabsana\Trade\Actions\TradeAction;
use Rabsana\Trade\Models\SymbolOrderTrade;

class TradeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->onQueue('bazar');
        $this->id = $id;
    }

    public function handle()
    {
        $symbolOrdertrade = SymbolOrderTrade::find($this->id);
        app(TradeAction::class)->run($symbolOrdertrade);
    }
}