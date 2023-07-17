<?php

use App\Http\Controllers\Home\GuestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'guest', 'as' => 'guest.'], function () {
    Route::get('dang-tin/{group}', [GuestController::class, 'add_classified'])->name('add-classified');
    Route::post('dang-tin/{group}', [GuestController::class, 'post_add_classified'])->name('post-add-classified');

    #khao sat website
    Route::post('website-survey', [GuestController::class, 'websiteSurvey']);
});
