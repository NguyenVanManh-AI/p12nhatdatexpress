<?php

use Illuminate\Support\Facades\Broadcast;

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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Broadcast::channel('chat-session-{chat_code}', function ($user, $chat_code){
//     return true;
// });

// Broadcast::channel('chat-user-{userId}', function ($user, $userId){
//     return true;
// });

// Broadcast::channel('chat-admin-{userId}', function ($user, $adminID){
//     return true;
// });

// Broadcast::channel('online', function ($user){
// //    \Illuminate\Support\Facades\Log::info('user admin: ' . $user);
//     return $user;
// });

// conversations
// Broadcast::channel('conversations.{token}', function ($user, $token) {
//   return true;

//   $conversation = $user->conversations()->where('token', $token)->first();

//   return $conversation ? true : false;
// });

// Broadcast::channel('user.{token}', function ($user, $token) {
//   return true;
//   return $user->channel_token === $token ? true : false;
// });

// Broadcast::channel('users', function ($user) {
//   return [
//     'id' => $user->id,
//     'name' => 'test',
//     'avatar' => null,
//   ];
// });

// conversations
Broadcast::channel('conversation.{token}', function ($user, $token) {
    return true;

    $conversation = $user->conversations()->where('token', $token)->first();

    return $conversation ? true : false;
});

Broadcast::channel('user.{token}', function ($user, $token) {
    return true;

    // $user user|admin
    return $user->channel_token === $token ? true : false;
});
