<?php

namespace Rabsana\Trade\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Rabsana\Trade\Models\SymbolOrder;

class OrdersHaveFilledEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $symbolOrders;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($symbolOrders)
    {
        $this->symbolOrders = SymbolOrder::whereIn('id', collect($symbolOrders)->pluck('id')->toArray())
            ->get();
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
