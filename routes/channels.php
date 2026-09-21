<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{userId1}.{userId2}',  function ($user, $userId1, $userId2){
    $currentUserId = (int) $user->id;
    $id1 = (int) $userId1;
    $id2 = (int) $userId2;

    return ($currentUserId === $id1 || $currentUserId === $id2);
});