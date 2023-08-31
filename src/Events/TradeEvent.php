<?php

namespace Rabsana\Trade\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Models\SymbolOrderTrade;

class TradeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $symbolOrderTrade;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SymbolOrderTrade $symbolOrderTrade)
    {
        $this->symbolOrderTrade = $symbolOrderTrade;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
