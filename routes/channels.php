<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('market.channel.{pair}', function (User $user) {
    if($user){
        return true;
    }

});

Broadcast::channel('market.user.channel.{userId}', function (User $user, $userId) {
    if($user->id == $userId){
        return true;
    } 
 
});

