<?php

use App\Http\Controllers\Admin\ChatBoxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Support\ChatController;
use App\Http\Controllers\Admin\SupportController;

Route::prefix('chat')->name('admin.support.')->middleware('admin.check:28,4')->group(function(){
    // should check middle for each route: example: admin.check:28,1 = thêm | admin.check:28,7 = xóa
    Route::get('/', [SupportController::class, 'index'])->name('index');
    Route::post('/message',[ChatController::class,'store_message'])->name('store');
    Route::post('/close/{chat_code}',[ChatController::class,'close_session'])->name('close');
    Route::get('/{code?}',[ChatController::class,'list'])->name('list');
});

Route::group(['prefix' => 'conversations', 'as' => 'admin.conversations.', 'middleware' => 'admin.check:28,4'], function() {
    Route::get('/close-conversation', [SupportController::class, 'closeConversation']);
    Route::post('/open-conversation', [SupportController::class, 'openConversation'])->name('openConversation');
    Route::post('/send-conversation-message', [SupportController::class, 'sendConversationMessage']);
    Route::post('/{conversation}/spam', [SupportController::class, 'spam'])->name('spam');
    Route::post('/{conversation}/un-spam', [SupportController::class, 'unSpam'])->name('un-spam');
    Route::post('/{conversation}/end', [SupportController::class, 'end'])->name('end');
    Route::get('/{token}/get-messages', [SupportController::class, 'getMessages']);
    Route::post('/{token}/read-conversation', [SupportController::class, 'readConversation']);
});

Route::group(['prefix' => 'chat-boxes', 'as' => 'chat-boxes.'], function() {
    Route::get('/get-unread-messages', [ChatBoxController::class, 'getUnreadMessages']);
});

