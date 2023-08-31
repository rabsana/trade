<?php

namespace Rabsana\Trade\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderPublicBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    private $channel;
    private $event;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel, string $event, array $data)
    {
        $this->data = $data;
        $this->channel = $channel;
        $this->event = $event;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [$this->channel];
    }

    public function broadcastAs()
    {
        return $this->event;
    }
}
