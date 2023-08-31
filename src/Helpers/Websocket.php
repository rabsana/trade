<?php

namespace Rabsana\Trade\Helpers;

use Pusher\Pusher;

class Websocket
{
    public static function stream(string $channel, string $event, array $data = [])
    {
        if (!config('rabsana-trade.pusher_is_active', false)) {
            return;
        }

        $pusher = new Pusher(
            config('rabsana-trade.pusher_app_key'),
            config('rabsana-trade.pusher_app_secret'),
            config('rabsana-trade.pusher_app_id'),
            config('rabsana-trade.pusher_options')
        );


        return $pusher->trigger(
            $channel,
            $event,
            $data
        );
    }
}
