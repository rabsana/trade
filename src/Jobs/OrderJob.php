<?php

namespace Rabsana\Trade\Jobs;


use Illuminate\Bus\Queueable;
use Rabsana\Trade\Models\SymbolOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Rabsana\Trade\Tasks\SendOrderWithWebsocketTask;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->onQueue('updateOrder');
        $this->id = $id;
    }

    public function handle()
    {
        $symbolOrder = SymbolOrder::find($this->id);
        app(SendOrderWithWebsocketTask::class)->run($symbolOrder, 'updated');        
    }
}